<!DOCTYPE html>
<html>
<head>
    <title>Duplicate Fund Notification</title>
</head>
<body>
    <h1>Duplicate Fund Notification</h1>

    <p>A duplicate fund has been detected:</p>

    <ul>
        <li>Existing Fund: #{{ $existingFund->id }} - {{ $existingFund->name }}</li>
        <li>New Fund: #{{ $newFund->id }} - {{ $newFund->name }}</li>
    </ul>

    <p>Both managed by: {{ $existingFund->manager->name }}</p>

    <p>The new fund has been created, but the existing fund has not been modified. Please review the new fund and merge it with the existing fund if appropriate.</p>

    <p>Thank you for your attention.</p>
</body>
</html>