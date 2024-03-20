<?php

function pp(
    $a,
    int     $exit   =0,
    string  $label  =''
) : void
{
    echo "<PRE>";
    if($label) echo "<h5>$label</h5>";
    if($label) echo "<title>$label</title>";
    echo "<pre>";
    print_r($a);
    echo '</pre>';
    if($exit) exit();
}


function fetch_weather() : array
{
    $weatherCodeLookup = [
        0 => 'Clear sky',
        1 => 'Mainly clear, partly cloudy, and overcast',
        2 => 'Partly cloudy',
        3 => 'Overcast',
        45 => 'Fog',
        48 => 'Depositing rime fog',
        51 => 'Light drizzle',
        53 => 'Moderate drizzle',
        55 => 'Dense drizzle',
        56 => 'Light freezing drizzle',
        57 => 'Dense freezing drizzle',
        61 => 'Slight rain',
        63 => 'Moderate rain',
        65 => 'Heavy rain',
        66 => 'Light freezing rain',
        67 => 'Heavy freezing rain',
        71 => 'Slight snow fall',
        73 => 'Moderate snow fall',
        75 => 'Heavy snow fall',
        77 => 'Snow grains',
        80 => 'Slight rain showers',
        81 => 'Moderate rain showers',
        82 => 'Violent rain showers',
        85 => 'Slight snow showers',
        86 => 'Heavy snow showers',
        95 => 'Thunderstorm',
        96 => 'Thunderstorm with slight hail',
        99 => 'Thunderstorm with heavy hail',
    ];
    $date = new DateTime("now", new DateTimeZone('Etc/GMT+5'));
    $date_f = $date->format('Y-m-d');
    $file_date = $date->format('Ymd');

    $file_name = "weather_".$file_date.".txt";
    if(file_exists($file_name)){
        return json_decode(file_get_contents($file_name), true);
    }

    $curl = curl_init();
    $url = "https://api.open-meteo.com/v1/forecast";
    $params = [
        "latitude" => 45.5088,
        "longitude" => -73.5878,
        "current_weather" => "true",
        "daily" => "weather_code,temperature_2m_max,temperature_2m_min",
    ];
    $queryString = http_build_query($params);

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url . "?" . $queryString,
        CURLOPT_USERAGENT => 'Open-Meteo PHP Request'
    ]);

    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);

    $weather = $response['daily'];
    $code = array_combine(array_values($weather['time']), array_values($weather['weather_code']));
    $min = array_combine(array_values($weather['time']), array_values($weather['temperature_2m_min']));
    $max = array_combine(array_values($weather['time']), array_values($weather['temperature_2m_max']));

    $today = [
        'code' => $weatherCodeLookup[$code[$date_f]],
        'min' => $min[$date_f],
        'max' => $max[$date_f],
    ];
    file_put_contents($file_name, json_encode($today));
    return $today;
}

function array_to_table(
    array $array,
    string  $label  =''
): void
{
    $keymap = array_keys(reset($array));
    if($label) echo "<h5>$label</h5>";

    $output = "<thead><tr>\n";
    foreach ($keymap as $key) {
        $output .= "<th>" . htmlspecialchars($key) . "</th>\n";
    }
    $output .= "</tr></thead>\n\n";

    foreach ($array as $row) {
        $output .= "<tr class='dataline'>\n";
        foreach ($keymap as $key) {
            $value = $row[$key] ?? '';
            $output .= "<td><div>" . htmlspecialchars($value) . "</div></td>\n";
        }
        $output .= "</tr>\n\n";
    }

    echo "<table class='default_table'>$output</table>";
}
$weather = fetch_weather();

$mood = "high";
switch($mood){
    case "high":
        $mood_emoji = "<i class=\"fas fa-smile\"></i>";
        break;
    case "low":
        $mood_emoji = "<i class=\"fas fa-frown\"></i>";
        break;
    default;
        $mood_emoji = "<i class=\"fas fa-meh\"></i>";
        break;
}

$focus = "high";
switch($focus){
    case "high":
        $focus_emoji = "<i class=\"fas fa-user-secret\"></i>";
        break;
    case "low":
        $focus_emoji = "<i class=\"fas fa-flushed\"></i>";
        break;
    default;
        $focus_emoji = "<i class=\"fas fa-meh\"></i>";
        break;
}


$user = [
    'name' => 'Stas'
];

$schedule = [
    [
        'Day'       => 'Monday',
        'Time'      => '18:00',
        'Activity'  => 'Hatha-Vinyansa Yoga',
    ],
    [
        'Day'       => 'Tuesday',
        'Time'      => '18:00',
        'Activity'  => 'Barre',
    ],
    [
        'Day'       => 'Wednesday',
        'Time'      => '18:00',
        'Activity'  => 'HIIT',
    ],
    [
        'Day'       => 'Wednesday',
        'Time'      => '18:30',
        'Activity'  => 'Boxing',
    ],
    [
        'Day'       => 'Thursday',
        'Time'      => '18:00',
        'Activity'  => 'Ashtange Yoga',
    ],
    [
        'Day'       => 'Friday',
        'Time'      => '08:00',
        'Activity'  => 'Boxing',
    ],
    [
        'Day'       => 'Friday',
        'Time'      => '12:00',
        'Activity'  => 'HIIT',
    ],
    [
        'Day'       => 'Friday',
        'Time'      => '12:40',
        'Activity'  => 'Hatha Yoga',
    ],
    [
        'Day'       => 'Saturday',
        'Time'      => '09:30',
        'Activity'  => 'HIIT',
    ],
    [
        'Day'       => 'Saturday',
        'Time'      => '10:00',
        'Activity'  => 'Boxing',
    ],
    [
        'Day'       => 'Saturday',
        'Time'      => '11:00',
        'Activity'  => 'Barre',
    ],
    [
        'Day'       => 'Sunday',
        'Time'      => '10:00',
        'Activity'  => 'Morning Flow Yoga',
    ],
];


?>
<!DOCTYPE html>
<html>
<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Varela+Round&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            --primary-color: #1EAAFC;
            --base-font-color: #d7d1d1;
            --font-color-1: #030209;
            --secondary-color-1: #ffff;
            --secondary-color-2: #000;
            --secondary-color-3: #1a1c22;
            --border-color: #676565;
            --bg-color-1: linear-gradient(180deg, #030209 0%, #0f0114 100%);
            --bg-color-2: conic-gradient(
                    from -69deg at 150% 116.45%,
                    #fff8f8 0deg,
                    #eb325d 360deg
            );
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }

        body {
            background-color: var(--secondary-color-3);
            font-size: 0.8rem;
            /*font-family: "Varela Round", sans-serif;*/
            color: var(--base-font-color);
            display: flex;
            justify-content: center;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }

        .container {
            /*border: 1px solid red;*/
            display: grid;
            width: 100vw;
            height: 95vh;
            grid-template-columns: 1fr 1fr 1fr;
            grid-template-rows: 50px 1fr 1fr 1fr 1fr 1fr 1fr;
            grid-gap: 1rem;
            grid-template-areas:
                  "header header header"
                  "profile-photo profile-card weather"
                  "weight mood focus"
                  "schedule schedule schedule"
                  "metric-1 metric-2 metric-3"
                  "metric-4 metric-5 metric-6"
                  "metric-7 metric-8 metric-9"
        }

        .item {
            /*background-color: #1EAAFC;*/
            /*background-image: linear-gradient(130deg, #6C52D9 0%, #1EAAFC 85%, #3EDFD7 100%);*/
            /*box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);*/
            /*border-radius: 4px;*/
            /*border: 6px solid #171717;*/
            color: #ffffff;
            /*display: flex;*/
            /*justify-content: center;*/
            /*align-items: center;*/
            font-size: 18px;
            font-weight: bold;
        }


        .header {
            grid-area: header;
            background-color: #1EAAFC;
            background-image: linear-gradient(130deg, #6C52D9 0%, #1EAAFC 85%, #3EDFD7 100%);
        }

        .profile-photo {
            grid-area: profile-photo;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            font-size: 2rem;
        }

        .profile-photo img {
            height: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--primary-color);
        }
        .profile-card {
            grid-area: profile-card;
            font-size: 3rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .weather {
            grid-area: weather;
            font-size: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .user-photo img {
            margin-right: 20px;
            width: 20rem;
            height: 20rem;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--primary-color);
        }

        .schedule {
            grid-area: schedule;
        }

        .weight {
            grid-area: weight;
        }
        .mood {
            grid-area: mood;
        }
        .focus {
            grid-area: focus;
        }

        .metric-2 {
            grid-area: metric-2;
            /*border: 1px solid var(--primary-color);*/
            border-radius: 20px;
        }
        .metric-3 {
            grid-area: metric-3;
            /*border: 1px solid var(--primary-color);*/
            border-radius: 20px;
        }
        .metric-4 {
            grid-area: metric-4;
            /*border: 1px solid var(--primary-color);*/
            border-radius: 20px;
        }
        .metric-5 {
            grid-area: metric-5;
            /*border: 1px solid var(--primary-color);*/
            border-radius: 20px;
        }
        .metric-6 {
            grid-area: metric-6;
            /*border: 1px solid var(--primary-color);*/
            border-radius: 20px;
        }
        .metric-7 {
            grid-area: metric-7;

        }
        .metric-8 {
            grid-area: metric-8;
        }
        .metric-9 {
            grid-area: metric-9;
        }

        .section-label {
            height: 10%;
            font-size: 2rem;
            padding-left: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .section-content {
            height: 90%;
            overflow: scroll;
            display: flex;
            justify-content: center;
        }

        .metric-label {
            height: 20%;
            font-size: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .metric-content {
            height: 80%;
            font-size: 5rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .unit {
            padding-left: 5px;
            font-size: 2rem;
        }

        table {
            width: 90%;
        }
        .schedule td {
            padding: 10px 15px;
            border-radius: 8px;
        }

        .schedule td:nth-of-type(1) {
            border: 2px solid var(--primary-color);
            background-color: var(--primary-color);
        }

        .schedule td:nth-of-type(2) {
            border: 2px solid var(--primary-color);
        }

        .schedule td:last-of-type {
            border: 2px solid var(--border-color);
        }

        .exercise-table td {
            padding: 5px;
        }


        .fa-smile, .fa-user-secret {
            color: #C3E2C2;
        }
        .fa-meh {
            color: #DBCC95;
        }
        .fa-frown, .fa-flushed{
            color: #9BB8CD;
        }



    </style>
</head>
<body>
<div class="container">

    <div class="item header"></div>

    <!-- Profile Card  -->
    <div class="item profile-photo">
        <img src="profile-pic.jpg"/>
    </div>
    <div class="item profile-card">
        <?=date("M D d")?>
    </div>
    <div class="item weather">
        <?php echo "{$weather['code']}"?><br>
        <?php echo "{$weather['min']} | {$weather['max']}"?>
    </div>

    <div class="item weight" >
        <div class="metric-label">Weight</div>
        <div class="metric-content">
            73.7<span class="unit">kg</span>
        </div>
    </div>
    <div class="item mood" >
        <div class="metric-label">Mood</div>
        <div class="metric-content">
            <?=$mood_emoji?>
        </div>
    </div>
    <div class="item focus" >
        <div class="metric-label">Focus</div>
        <div class="metric-content">
            <?=$focus_emoji?>
        </div>
    </div>

    <!-- Schedule  -->
    <div class="item schedule">
        <div class="section-label">Schedule</div>
        <div class="section-content">
            <?php array_to_table($schedule) ?>
        </div>
    </div>

    <!-- Metrics  -->
    <div class="item metric-1" onclick="window.location.replace('metric.php?m=calorie-intake')">
        <div class="metric-label">Calorie Intake</div>
        <div class="metric-content">
            1750<span class="unit">cal</span>
        </div>
    </div>
    <div class="item metric-2">
        <div class="metric-label">Protein Intake</div>
        <div class="metric-content">
            120<span class="unit">g</span>
        </div>
    </div>
    <div class="item metric-3">
        <div class="metric-label">Water InTake</div>
        <div class="metric-content">
            5<span class="unit">L</span>
        </div>
    </div>

    <!-- Fitness  -->
    <div class="item metric-4">
        <div class="metric-label">Active Calories</div>
        <div class="metric-content">
            1615<span class="unit">cal</span>
        </div>
    </div>
    <div class="item metric-5">
        <div class="metric-label">PullUps</div>
        <div class="metric-content">
            0<span class="unit">reps</span>
        </div>
    </div>

    <div class="item metric-6">
        <div class="metric-label">Pushups</div>
        <div class="metric-content">
            0<span class="unit">reps</span>
        </div>
    </div>

    <div class="item metric-7">
    </div>

    <div class="item metric-8">
    </div>
    <div class="item metric-9">
    </div>
</div>
</body>
</html>
