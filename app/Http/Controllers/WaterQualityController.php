<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class WaterQualityController extends Controller
{
    private $channelID = "2964378";
    private $apiKey = "J007XMWSBU6301WM";
    private $baseURL = "https://api.thingspeak.com/channels";

    /**
     * Obtener todos los datos almacenados en la base de datos.
     */
    public function getData()
    {
        try {
            $data = DB::table('water_quality_data')
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting water quality data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos de la base de datos'
            ], 500);
        }
    }

    /**
     * Obtener datos desde ThingSpeak y almacenarlos en la base de datos.
     */
    public function fetchData(Request $request)
    {
        try {
            // Crear tabla si no existe
            $this->createTableIfNotExists();

            // Obtener datos del canal ThingSpeak
            $feeds = $this->fetchFromThingSpeak();

            // Guardar en la base de datos
            $savedCount = $this->saveToDatabase($feeds);

            return response()->json([
                'success' => true,
                'saved_count' => $savedCount,
                'message' => "Datos obtenidos y {$savedCount} registros guardados correctamente"
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching water quality data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar todos los datos de la tabla.
     */
    public function clearData()
    {
        try {
            DB::table('water_quality_data')->delete();

            return response()->json([
                'success' => true,
                'message' => 'Base de datos limpiada correctamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error clearing water quality data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al limpiar la base de datos'
            ], 500);
        }
    }

    /**
     * Crear tabla si no existe.
     */
    private function createTableIfNotExists()
    {
        if (!Schema::hasTable('water_quality_data')) {
            Schema::create('water_quality_data', function (Blueprint $table) {
                $table->id();
                $table->integer('entry_id')->unique();
                $table->decimal('conductivity', 8, 2)->nullable();
                $table->decimal('ph', 8, 2)->nullable();
                $table->decimal('oxygen', 8, 2)->nullable();
                $table->decimal('temperature', 8, 2)->nullable();
                $table->timestamps(); // ✅ crea created_at y updated_at
            });
        }
    }

    /**
     * Descargar datos del canal ThingSpeak.
     */
    private function fetchFromThingSpeak()
    {
        $url = "{$this->baseURL}/{$this->channelID}/feeds.json?api_key={$this->apiKey}&results=8000";

        $response = Http::get($url);

        if (!$response->successful()) {
            throw new \Exception('No se pudieron obtener datos de ThingSpeak');
        }

        $data = $response->json();

        if (!isset($data['feeds']) || empty($data['feeds'])) {
            throw new \Exception('No hay datos disponibles en el canal');
        }

        return $data['feeds'];
    }

    /**
     * Guardar datos en la base de datos.
     */
    private function saveToDatabase($feeds)
    {
        $savedCount = 0;

        foreach ($feeds as $feed) {
            try {
                // Verificar si el registro ya existe
                $existing = DB::table('water_quality_data')
                    ->where('entry_id', $feed['entry_id'])
                    ->first();

                if (!$existing) {
                    DB::table('water_quality_data')->insert([
                        'entry_id' => $feed['entry_id'],
                        'conductivity' => $feed['field1'] ?? null,
                        'ph' => $feed['field2'] ?? null,
                        'oxygen' => $feed['field3'] ?? null,
                        'temperature' => $feed['field4'] ?? null,
                        // Usa la fecha original del feed si está disponible
                        'created_at' => $feed['created_at'] ?? now(),
                        'updated_at' => now()
                    ]);
                    $savedCount++;
                }
            } catch (\Exception $e) {
                Log::warning("Error saving entry {$feed['entry_id']}: " . $e->getMessage());
                continue;
            }
        }

        return $savedCount;
    }
}
