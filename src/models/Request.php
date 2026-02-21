<?php
require_once __DIR__ . '/../db.php';

class RequestModel {
    public static function create($data) {
        global $db;
        $stmt = $db->prepare("INSERT INTO requests (clientName, phone, address, problemText) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['clientName'], $data['phone'], $data['address'], $data['problemText']]);
        return $db->lastInsertId();
    }

    public static function all($status = null) {
        global $db;
        if ($status) {
            $stmt = $db->prepare("SELECT * FROM requests WHERE status=? ORDER BY createdAt DESC");
            $stmt->execute([$status]);
        } else {
            $stmt = $db->query("SELECT * FROM requests ORDER BY createdAt DESC");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM requests WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updateStatus($id, $status, $assignedTo = null) {
        global $db;
        $stmt = $db->prepare("UPDATE requests SET status=?, assignedTo=?, updatedAt=CURRENT_TIMESTAMP WHERE id=?");
        $stmt->execute([$status, $assignedTo, $id]);
    }

    public static function takeInProgress($requestId, $masterId) {
        global $db;

        try {
            // Начало транзакции
            $db->beginTransaction();

            // Атомарный UPDATE: меняем статус только если он assigned
            $stmt = $db->prepare("
                UPDATE requests
                SET status = 'in_progress', assignedTo = ?
                WHERE id = ? AND status = 'assigned'
            ");
            $stmt->execute([$masterId, $requestId]);

            if ($stmt->rowCount() === 0) {
                // Никто не обновил → заявка уже взята или не найдена
                $db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Заявка уже взята или не существует',
                    'code' => 409
                ];
            }

            $db->commit();
            return [
                'success' => true,
                'message' => 'Заявка взята в работу'
            ];
        } catch (Exception $e) {
            $db->rollBack();
            return [
                'success' => false,
                'message' => 'Ошибка базы: ' . $e->getMessage(),
                'code' => 500
            ];
        }
    }
    
    public static function allWithMaster() {
    global $db;
    $stmt = $db->query("
        SELECT r.*, u.username AS masterName
        FROM requests r
        LEFT JOIN users u ON r.assignedTo = u.id
        ORDER BY r.createdAt DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}