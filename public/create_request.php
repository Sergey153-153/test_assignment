<form method="POST" action="index.php">
    <input type="hidden" name="action" value="create">
    <input name="clientName" placeholder="Client Name" required><br>
    <input name="phone" placeholder="Phone" required><br>
    <input name="address" placeholder="Address" required><br>
    <textarea name="problemText" placeholder="Problem description" required></textarea><br>
    <button type="submit">Создать заявку</button>
</form>