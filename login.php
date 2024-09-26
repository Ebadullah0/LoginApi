<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
</head>
<body>
    <form action="index.php" method="POST">
        <label for="employee_name">Employee Name:</label>
        <input type="text" id="employee_name" name="employee_name" required>
        
        <label for="employee_id">Employee ID:</label>
        <input type="text" id="employee_id" name="employee_id" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <input type="submit" value="Login">
    </form>
</body>
</html>
