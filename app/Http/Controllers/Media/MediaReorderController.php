<?php

namespace App\Http\Controllers\Media;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Controllers\Controller;

class MediaReorderController extends Controller
{
    public function reorder(Request $request, int $modelId)
    {
        $request->validate([
          'data' => 'required|string',
          'model_type' => 'sometimes|string',
        ]);

        // Получаем текущие медиа в порядке их order_column
        $currentMedia = Media::where('model_id', $modelId)
                             ->where('model_type', 'App\Models\Product')
                             ->orderBy('order_column')
                             ->get();

        // Получаем новые позиции (индексы)
        $newOrder = array_map('intval', explode(',', $request->input('data')));

        Log::debug('Current media IDs', $currentMedia->pluck('id')->toArray());
        Log::debug('New positions', $newOrder);

        // Создаем массив для новых значений order_column
        $updates = [];

        // $newOrder содержит индексы элементов из $currentMedia в новом порядке
        foreach ($newOrder as $newPosition => $oldIndex) {
            if (isset($currentMedia[$oldIndex])) {
                $mediaId = $currentMedia[$oldIndex]->id;
                $updates[$mediaId] = $newPosition + 1; // +1 так как order_column начинается с 1
            }
        }

        Log::debug('Updates to apply', $updates);

        // Применяем обновления
        DB::transaction(function () use ($updates, $modelId) {
            foreach ($updates as $mediaId => $orderColumn) {
                Media::where('id', $mediaId)
                     ->where('model_id', $modelId)
                     ->update(['order_column' => $orderColumn]);
            }
        });

        return response()->json([
          'success' => true,
          'message' => 'Порядок изображений успешно обновлен'
        ]);
    }
}