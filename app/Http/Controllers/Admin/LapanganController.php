<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;

class LapanganController extends Controller
{
    protected $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function index()
    {
        $lapangans = Lapangan::latest()->get();
        return view('admin.data_lapangan', compact('lapangans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'required|string',
            'harga_siang' => 'required|numeric|min:0',
            'harga_malam' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,tidak_tersedia',
        ]);

        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Enhanced image processing
                $processedImage = $this->processAndCompressImage($image);
                
                if (!$processedImage) {
                    throw new \Exception('Image processing failed');
                }

                $storagePath = 'lapangan/' . date('Y/m');
                $uniqueFileName = uniqid('lapangan_') . '.jpg';
                
                // Store processed image
                $imagePath = Storage::disk('public')->put(
                    $storagePath . '/' . $uniqueFileName, 
                    $processedImage['image']->toString()
                );

                $validated['image'] = $storagePath . '/' . $uniqueFileName;

                // Detailed logging
                Log::info('Image Upload Details', [
                    'original_name' => $image->getClientOriginalName(),
                    'unique_filename' => $uniqueFileName,
                    'storage_path' => $storagePath,
                    'full_path' => $validated['image'],
                    'file_size_original' => $image->getSize(),
                    'file_size_processed' => strlen($processedImage['image']),
                    'compression_ratio' => round(strlen($processedImage['image']) / $image->getSize() * 100, 2) . '%'
                ]);
            }

            $lapangan = Lapangan::create($validated);

            return redirect()->route('admin.data.lapangan')
                ->with('success', 'Lapangan berhasil ditambahkan');

        } catch (\Exception $e) {
            Log::error('Lapangan Creation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input_data' => $request->except('image')
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Gagal menambahkan lapangan. Silakan coba lagi.'])
                ->withInput($request->except('image'));
        }
    }

    private function processAndCompressImage($image)
    {
        try {
            // Validate image
            if (!$image->isValid()) {
                Log::warning('Invalid image upload', ['file' => $image->getClientOriginalName()]);
                return false;
            }

            // Get image extension
            $extension = $image->getClientOriginalExtension();

            // Use Intervention Image for processing
            $processedImage = $this->imageManager->read($image->getRealPath());

            // Resize image while maintaining aspect ratio
            $processedImage->scale(width: 800);

            // Convert to JPEG with compression
            $jpegImage = $processedImage->toJpeg(quality: 75);

            return [
                'image' => $jpegImage,
                'extension' => 'jpg' // Always convert to jpg for consistency
            ];

        } catch (\Exception $e) {
            Log::error('Image Processing Error', [
                'message' => $e->getMessage(),
                'file' => $image->getClientOriginalName()
            ]);
            return false;
        }
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'required|string',
            'harga_siang' => 'required|numeric|min:0',
            'harga_malam' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,tidak_tersedia',
        ]);

        try {
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($lapangan->image) {
                    Storage::disk('public')->delete($lapangan->image);
                }
                
                // Process and compress new image
                $image = $request->file('image');
                $processedImage = $this->processAndCompressImage($image);
                
                if (!$processedImage) {
                    throw new \Exception('Image processing failed');
                }

                $storagePath = 'lapangan/' . date('Y/m');
                $uniqueFileName = uniqid('lapangan_') . '.jpg';
                
                // Store processed image
                $imagePath = Storage::disk('public')->put(
                    $storagePath . '/' . $uniqueFileName, 
                    $processedImage['image']->toString()
                );

                $validated['image'] = $storagePath . '/' . $uniqueFileName;

                // Detailed logging
                Log::info('Image Update Details', [
                    'lapangan_id' => $lapangan->id,
                    'original_name' => $image->getClientOriginalName(),
                    'unique_filename' => $uniqueFileName,
                    'storage_path' => $storagePath,
                    'full_path' => $validated['image'],
                    'file_size_original' => $image->getSize(),
                    'file_size_processed' => strlen($processedImage['image']),
                    'compression_ratio' => round(strlen($processedImage['image']) / $image->getSize() * 100, 2) . '%'
                ]);
            } else {
                // Remove image key if no new image is uploaded
                unset($validated['image']);
            }

            // Update lapangan
            $lapangan->update($validated);

            return redirect()->route('admin.data.lapangan')
                ->with('success', 'Lapangan berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Lapangan Update Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'lapangan_id' => $lapangan->id,
                'input_data' => $request->except('image')
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Gagal memperbarui lapangan. Silakan coba lagi.'])
                ->withInput($request->except('image'));
        }
    }

    public function destroy(Lapangan $lapangan)
    {
        // Delete image
        if ($lapangan->image) {
            Storage::delete('public/' . $lapangan->image);
        }

        $lapangan->delete();

        return redirect()->route('admin.data.lapangan')
            ->with('success', 'Lapangan berhasil dihapus');
    }
} 