<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notifications extends BaseController
{
    public function get()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Not authenticated',
            ]);
        }

        $userId = (int) session()->get('user_id');
        $model  = new NotificationModel();

        $count = $model->getUnreadCount($userId);
        $list  = $model->getNotificationsForUser($userId, 5);

        return $this->response->setJSON([
            'status'    => 'success',
            'count'     => $count,
            'items'     => $list,
            'csrfToken' => csrf_token(),
            'csrfHash'  => csrf_hash(),
        ]);
    }

    public function mark_as_read($id)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Not authenticated',
            ]);
        }

        $userId = (int) session()->get('user_id');
        $id     = (int) $id;

        $model = new NotificationModel();
        $ok    = $model->markAsRead($id, $userId);

        $newCount = $model->getUnreadCount($userId);

        return $this->response->setJSON([
            'status'    => $ok ? 'success' : 'error',
            'message'   => $ok ? 'Marked as read' : 'Failed to mark as read',
            'count'     => $newCount,
            'csrfToken' => csrf_token(),
            'csrfHash'  => csrf_hash(),
        ]);
    }
}
