<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            [
                'name' => 'Mecanización Agrícola y Pecuaria',
                'description' => 'Sistemas y funcionamiento del tractor - Mantenimiento preventivo - Implementos para preparación primaria y secundaria del suelo',
                'instructor' => 'Edwin Ramírez Vasquez',
                'duration_minutes' => 240, // 4 horas
                'max_participants' => 30,
                'requirements' => 'Ropa cómoda, zapatos cerrados, camisa manga larga, gorra para protección solar',
                'location' => 'Área de maquinaria agrícola'
            ],
            [
                'name' => 'Práctica de Campo - Operación de Maquinaria',
                'description' => 'Práctica de campo: operación de maquinaria con tractor',
                'instructor' => 'Edwin Ramírez Vasquez',
                'duration_minutes' => 180, // 3 horas
                'max_participants' => 20,
                'requirements' => 'Botas industriales o de goma, ropa de trabajo, protección personal',
                'location' => 'Campo de práctica'
            ],
            [
                'name' => 'Sistemas de Riego y Drenajes',
                'description' => 'Sistemas de riego y drenajes - Tipos de riego: gravedad, aspersión, microaspersión y goteo - Generalidades sobre drenajes - Recorrido por los cultivos de la institución',
                'instructor' => 'Juan de Dios Nañez',
                'duration_minutes' => 270, // 4.5 horas
                'max_participants' => 25,
                'requirements' => 'Ropa cómoda, zapatos cerrados, gorra para protección solar',
                'location' => 'Área de cultivos y sistemas de riego'
            ],
            [
                'name' => 'Recorrido por Unidades Pecuarias',
                'description' => 'Recorrido por unidades pecuarias',
                'instructor' => 'Gestores Sena Empresa',
                'duration_minutes' => 180, // 3 horas
                'max_participants' => 20,
                'requirements' => 'Botas industriales o de goma obligatorias, ropa de trabajo',
                'location' => 'Unidades pecuarias'
            ],
            [
                'name' => 'Manejo de Calidad en Cacao',
                'description' => 'Manejo de calidad en cacao',
                'instructor' => 'Kathryn Yadira Guzman',
                'duration_minutes' => 120, // 2 horas
                'max_participants' => 15,
                'requirements' => 'Ropa cómoda, zapatos cerrados',
                'location' => 'Laboratorio de cacao'
            ],
            [
                'name' => 'Práctica de Calidad en Cacao',
                'description' => 'Práctica de calidad en cacao',
                'instructor' => 'Kathryn Yadira Guzman',
                'duration_minutes' => 90, // 1.5 horas
                'max_participants' => 15,
                'requirements' => 'Ropa cómoda, zapatos cerrados',
                'location' => 'Laboratorio de cacao'
            ],
            [
                'name' => 'Práctica de Laboratorio de Biotecnología',
                'description' => 'Práctica de laboratorio de biotecnología',
                'instructor' => 'Kathryn Yadira Guzman',
                'duration_minutes' => 89, // 1 hora 29 minutos
                'max_participants' => 12,
                'requirements' => 'Batas de laboratorio obligatorias, ropa cómoda, zapatos cerrados',
                'location' => 'Laboratorio de biotecnología'
            ],
            [
                'name' => 'Práctica de Laboratorio de Suelo',
                'description' => 'Práctica de laboratorio de suelo',
                'instructor' => 'Kathryn Yadira Guzman',
                'duration_minutes' => 90, // 1.5 horas
                'max_participants' => 12,
                'requirements' => 'Batas de laboratorio obligatorias, ropa cómoda, zapatos cerrados',
                'location' => 'Laboratorio de suelo'
            ],
            [
                'name' => 'Tour General de la Institución',
                'description' => 'Recorrido general por las instalaciones de la institución educativa',
                'instructor' => 'Gestores Sena Empresa',
                'duration_minutes' => 120, // 2 horas
                'max_participants' => 30,
                'requirements' => 'Ropa cómoda, zapatos cerrados, gorra para protección solar',
                'location' => 'Todas las instalaciones'
            ]
        ];

        foreach ($activities as $activity) {
            DB::table('visit_activities')->insert($activity);
        }
    }
}
