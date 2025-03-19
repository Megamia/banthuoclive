<?php

namespace Betod\Livotec\Controllers\Product;

use Backend\Classes\Controller;
use Illuminate\Http\Request;
use Betod\Livotec\Models\Product;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;

class ImportCSV extends Controller
{
    public function importCsv()
    {
        try {
            if (!request()->hasFile('csv_file')) {
                throw new \Exception('Không tìm thấy file CSV.');
            }

            $file = request()->file('csv_file');

            if (!$file) {
                throw new \Exception('Không thể đọc file CSV.');
            }

            if ($file->getClientOriginalExtension() !== 'csv') {
                throw new \Exception('File không đúng định dạng CSV.');
            }

            try {
                $csv = Reader::createFromPath($file->getPathname(), 'r');
                $csv->setHeaderOffset(0);
            } catch (\Exception $e) {
                throw new \Exception('Lỗi khi đọc file CSV: ' . $e->getMessage());
            }

            $headers = array_map('trim', $csv->getHeader());

            $requiredColumns = ['name', 'slug', 'available', 'sold_out', 'stock'];
            foreach ($requiredColumns as $column) {
                if (!in_array($column, $headers)) {
                    throw new \Exception("Thiếu cột '{$column}' trong file CSV.");
                }
            }

            $importedCount = 0;

            foreach ($csv as $row) {
                $row = array_map('trim', $row);

                if (empty($row['name']) || empty($row['slug'])) {
                    Log::warning('Bỏ qua dòng do dữ liệu thiếu name hoặc slug.', $row);
                    continue;
                }

                Product::updateOrCreate(
                    ['slug' => $row['slug']],
                    [
                        'name' => $row['name'],
                        'available' => (int) $row['available'],
                        'sold_out' => (int) $row['sold_out'],
                        'stock' => (int) $row['stock'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );

                $importedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "Đã import thành công {$importedCount} sản phẩm!"
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi import CSV: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
