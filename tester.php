<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website with Single-Line Footer</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Page and Content Styling */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        /* Single-Line Footer Styling */
        .footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            font-size: 0.9em;
            white-space: nowrap;
        }

        .footer span {
            font-weight: bold;
        }

        /* Optional link styling */
        .footer a {
            color: #ddd;
            text-decoration: none;
            margin: 0 5px;
        }

        .footer a:hover {
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Main Content -->
    <div class="content">
        <h1>Welcome to AITS School Website</h1>
        <p>This is the main content of the website. The footer stays at the bottom and doesn’t interfere with the content.</p>
    </div>

    <!-- Single-Line Footer Section -->
    <footer class="footer">
        © 2024 AITS BulSU Meneses Campus. All rights reserved. Group Members: <span>Jerick De Guzman</span>, <span>Rick Jason Garcia</span>, <span>Andro Marc Valdez</span>, <span>Angelo Velasco</span>
    </footer>

</body>
</html>
