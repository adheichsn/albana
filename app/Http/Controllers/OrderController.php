<?php

namespace App\Http\Controllers;

use App\Models\PostOrder;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function complete($id)
    {
        // Logic to mark the order as complete
        $postorder = PostOrder::findOrFail($id);
        $postorder->status_paket = 'completed'; // Assuming you have a status field
        $postorder->save();

        // Send success notification
        Notification::make()
            ->title('Order Completed')
            ->body('The order has been marked as completed.')
            ->success()
            ->send();

        return redirect()->back();
    }

    public function reject($id)
    {
        // Logic to reject the order
        $postorder = PostOrder::findOrFail($id);9
        $postorder->status_paket = 'Return'; // Assuming you have a status field
        $postorder->save();

        // Send success notification
        Notification::make()
            ->title('Order Rejected')
            ->body('The order has been rejected.')
            ->danger()
            ->send();

        return redirect()->back();
    }
}
