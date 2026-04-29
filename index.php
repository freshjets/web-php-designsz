<?php
// Sets the default timezone to ensure the server time aligns with local time
date_default_timezone_set('Europe/Berlin');

function build_calendar($month, $year) {
    // Array containing names of days of the week
    $daysOfWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

    // First day of the month
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    
    // Number of days in the month
    $numberDays = date('t', $firstDayOfMonth);
    
    // Get details of the first day
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    // Start building the table
    $calendar = "<table class='calendar'>";
    $calendar .= "<caption>$monthName $year</caption>";
    $calendar .= "<tr>";

    // Create the calendar headers
    foreach ($daysOfWeek as $day) {
        $calendar .= "<th>$day</th>";
    }
    $calendar .= "</tr><tr>";

    // Fill the empty cells before the first day of the month
    if ($dayOfWeek > 0) {
        for ($k = 0; $k < $dayOfWeek; $k++) {
            $calendar .= "<td></td>";
        }
    }

    $currentDay = 1;
    $monthPadded = str_pad($month, 2, "0", STR_PAD_LEFT);

    while ($currentDay <= $numberDays) {
        // If we reach the 7th column (Saturday), start a new row
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$monthPadded-$currentDayRel";
        
        // Highlight today's date
        $todayClass = ($date == date('Y-m-d')) ? "class='today'" : "";
        
        $calendar .= "<td $todayClass>$currentDay</td>";
        
        $currentDay++;
        $dayOfWeek++;
    }

    // Fill the remaining empty cells at the end of the month
    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($l = 0; $l < $remainingDays; $l++) {
            $calendar .= "<td></td>";
        }
    }

    $calendar .= "</tr></table>";
    return $calendar;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Time & Calendar</title>
    <style>
        body {
            background-color: #000000;
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        #clock {
            font-size: 4rem;
            margin-bottom: 30px;
            font-weight: 300;
            letter-spacing: 4px;
            color: #eeeeee;
        }

        .calendar {
            border-collapse: collapse;
            width: 350px;
            background-color: #111111;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.05);
        }

        .calendar caption {
            font-size: 1.4rem;
            padding: 15px;
            font-weight: 600;
            background-color: #1a1a1a;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .calendar th, .calendar td {
            padding: 12px;
            text-align: center;
            border: 1px solid #222222;
        }

        .calendar th {
            background-color: #1a1a1a;
            color: #888888;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .calendar td {
            color: #dddddd;
        }

        .calendar td.today {
            background-color: #ffffff;
            color: #000000;
            font-weight: bold;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div id="clock">
        <?php echo date("H:i:s"); ?>
    </div>

    <div>
        <?php
        $dateComponents = getdate();
        $month = $dateComponents['mon'];
        $year = $dateComponents['year'];
        echo build_calendar($month, $year);
        ?>
    </div>

    <script>
        // JavaScript to update the clock every second without refreshing the page
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            document.getElementById('clock').textContent = hours + ':' + minutes + ':' + seconds;
        }

        // Run the clock update every 1000 milliseconds (1 second)
        setInterval(updateClock, 1000);
    </script>

</body>
</html>
