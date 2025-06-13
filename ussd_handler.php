<?php

function handleUssd($text, $phoneNumber, $conn) {
    $parts = explode("*", $text);
    $level = count($parts);

    $isAdmin = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM admins WHERE phone = '$phoneNumber'")) > 0;

    if ($text == "") {
        if ($isAdmin) {
            return "CON Welcome Admin. Select Option:\n1. View Pending Appeals\n2. Update Appeal Status\n3. Register Student Marks\n4. Update Existing Marks\n5. Exit";
        } else {
            return "CON Welcome Student. Enter your Student ID:";
        }
    }

    // ================= ADMIN FLOW ===================
    if ($isAdmin) {
        $choice = $parts[0];
        if ($choice == "5") return "END Thank you. Goodbye.";

        if ($level == 1) {
            if ($choice == "1") {
                $result = mysqli_query($conn, "SELECT a.appeal_id, a.student_id, m.module_name FROM appeals a JOIN modules m ON a.module_id = m.module_id WHERE a.status = 'Pending' LIMIT 5");
                if (mysqli_num_rows($result) == 0) return "END No pending appeals.";

                $response = "END Pending Appeals:\n";
                while ($row = mysqli_fetch_assoc($result)) {
                    $response .= "ID: {$row['appeal_id']} | Student: {$row['student_id']} | {$row['module_name']}\n";
                }
                return $response;
            } elseif ($choice == "2") {
                return "CON Enter Appeal ID to update:";
            } elseif ($choice == "3") {
                return "CON Enter Student ID:";
            } elseif ($choice == "4") {
                return "CON Enter Student ID:";
            } else {
                return "END Invalid admin option.";
            }
        }

        if ($choice == "2") {
            if ($level == 2) return "CON Select new status:\n1. Under Review\n2. Resolved\n0. Go Back";
            if ($level == 3) {
                if ($parts[2] == "0") return "CON Enter Appeal ID to update:";
                $appealId = intval($parts[1]);
                $status = $parts[2] == "1" ? "Under Review" : "Resolved";
                mysqli_query($conn, "UPDATE appeals SET status = '$status' WHERE appeal_id = $appealId");
                return "END Appeal status updated to '$status'";
            }
        }

        if ($choice == "3") {
            if ($level == 2) return "CON Enter Student Name:";
            if ($level == 3) return "CON Enter Module Name:";
            if ($level == 4) return "CON Enter Mark (0-100):";
            if ($level == 5) {
                $studentId = $parts[1];
                $name = $parts[2];
                $moduleName = $parts[3];
                $mark = intval($parts[4]);

                if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$studentId'")) == 0) {
                    mysqli_query($conn, "INSERT INTO students (student_id, name) VALUES ('$studentId', '$name')");
                }
                if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM modules WHERE module_name = '$moduleName'")) == 0) {
                    mysqli_query($conn, "INSERT INTO modules (module_name) VALUES ('$moduleName')");
                }
                $moduleRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT module_id FROM modules WHERE module_name = '$moduleName'"));
                $moduleId = $moduleRow['module_id'];
                mysqli_query($conn, "INSERT INTO marks (student_id, module_id, mark) VALUES ('$studentId', '$moduleId', '$mark')");
                return "END Mark registered successfully.";
            }
        }

        if ($choice == "4") {
            if ($level == 2) return "CON Enter Module Name:";
            if ($level == 3) return "CON Enter New Mark (0-100):";
            if ($level == 4) {
                $studentId = $parts[1];
                $moduleName = $parts[2];
                $newMark = intval($parts[3]);

                if ($newMark < 0 || $newMark > 100) return "END Invalid mark. Must be between 0 and 100.";
                $moduleRes = mysqli_query($conn, "SELECT module_id FROM modules WHERE module_name = '$moduleName'");
                if (mysqli_num_rows($moduleRes) == 0) return "END Module not found.";
                $moduleId = mysqli_fetch_assoc($moduleRes)['module_id'];

                if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM marks WHERE student_id = '$studentId' AND module_id = '$moduleId'")) == 0)
                    return "END Mark record not found.";

                mysqli_query($conn, "UPDATE marks SET mark = '$newMark' WHERE student_id = '$studentId' AND module_id = '$moduleId'");
                return "END Mark updated successfully.";
            }
        }
    }

    // ================= STUDENT FLOW ===================
    if (!$isAdmin) {
        if ($level == 1) return "CON Select Option:\n1. Check Marks\n2. Appeal My Marks\n3. Exit";

        $studentId = $parts[0];
        $studentCheck = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$studentId'");
        if (mysqli_num_rows($studentCheck) == 0) return "END Error: Student ID not found.";

        if ($level == 2 && $parts[1] == "1") {
            $result = mysqli_query($conn, "SELECT m.module_name, mk.mark FROM marks mk JOIN modules m ON mk.module_id = m.module_id WHERE student_id = '$studentId'");
            if (mysqli_num_rows($result) == 0) return "END No marks found.";

            $response = "END Your marks for the module:\n";
            while ($row = mysqli_fetch_assoc($result)) {
                $response .= $row['module_name'] . ": " . $row['mark'] . "\n";
            }
            return $response;
        }

        if ($level == 2 && $parts[1] == "2") {
            $modules = mysqli_query($conn, "SELECT m.module_id, m.module_name, mk.mark FROM marks mk JOIN modules m ON mk.module_id = m.module_id WHERE student_id = '$studentId'");
            if (mysqli_num_rows($modules) == 0) return "END No modules found.";

            $GLOBALS['moduleMap'] = [];
            $response = "CON Select module to appeal:\n";
            $i = 1;
            while ($row = mysqli_fetch_assoc($modules)) {
                $GLOBALS['moduleMap'][$i] = $row['module_id'];
                $response .= "$i. {$row['module_name']} : {$row['mark']}\n";
                $i++;
            }
            $response .= "0. Go Back";
            return $response;
        }

        if ($level == 3) {
            if ($parts[2] == "0") return "CON Select Option:\n1. Check Marks\n2. Appeal My Marks\n3. Exit";
            return "CON Please provide a brief reason for your appeal:";
        }

        if ($level == 4) {
            $moduleChoice = intval($parts[2]);
            $reason = $parts[3];

            $result = mysqli_query($conn, "SELECT module_id FROM marks WHERE student_id = '$studentId'");
            $moduleIds = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $moduleIds[] = $row['module_id'];
            }

            if (!isset($moduleIds[$moduleChoice - 1])) return "END Invalid module selection.";
            $selectedModuleId = $moduleIds[$moduleChoice - 1];

            mysqli_query($conn, "INSERT INTO appeals (student_id, module_id, reason, status) VALUES ('$studentId', '$selectedModuleId', '$reason', 'Pending')");
            return "END Appeal submitted successfully.";
        }

        if ($level == 2 && $parts[1] == "3") return "END Thank you. Goodbye.";
    }

    return "END Invalid request.";
}
