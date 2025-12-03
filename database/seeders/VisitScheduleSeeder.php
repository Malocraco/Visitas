<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = [
            // Lunes
            [
                'day_of_week' => 'Monday',
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'max_visits_per_slot' => 2,
                'notes' => 'Horario matutino - Actividades de mecanización agrícola'
            ],
            [
                'day_of_week' => 'Monday',
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
                'max_visits_per_slot' => 2,
                'notes' => 'Horario vespertino - Prácticas de campo'
            ],
            // Martes
            [
                'day_of_week' => 'Tuesday',
                'start_time' => '07:30:00',
                'end_time' => '12:00:00',
                'max_visits_per_slot' => 2,
                'notes' => 'Horario matutino - Sistemas de riego y drenajes'
            ],
            [
                'day_of_week' => 'Tuesday',
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
                'max_visits_per_slot' => 2,
                'notes' => 'Horario vespertino - Unidades pecuarias'
            ],
            // Miércoles
            [
                'day_of_week' => 'Wednesday',
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'max_visits_per_slot' => 1,
                'notes' => 'Manejo de calidad en cacao'
            ],
            [
                'day_of_week' => 'Wednesday',
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'max_visits_per_slot' => 1,
                'notes' => 'Práctica de calidad en cacao'
            ],
            [
                'day_of_week' => 'Wednesday',
                'start_time' => '13:00:00',
                'end_time' => '14:29:00',
                'max_visits_per_slot' => 1,
                'notes' => 'Práctica de laboratorio de biotecnología'
            ],
            [
                'day_of_week' => 'Wednesday',
                'start_time' => '14:30:00',
                'end_time' => '16:00:00',
                'max_visits_per_slot' => 1,
                'notes' => 'Práctica de laboratorio de suelo'
            ],
            // Jueves
            [
                'day_of_week' => 'Thursday',
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'max_visits_per_slot' => 2,
                'notes' => 'Horario matutino - Actividades generales'
            ],
            [
                'day_of_week' => 'Thursday',
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
                'max_visits_per_slot' => 2,
                'notes' => 'Horario vespertino - Actividades generales'
            ],
            // Viernes
            [
                'day_of_week' => 'Friday',
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'max_visits_per_slot' => 2,
                'notes' => 'Horario matutino - Actividades generales'
            ],
            [
                'day_of_week' => 'Friday',
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
                'max_visits_per_slot' => 2,
                'notes' => 'Horario vespertino - Actividades generales'
            ]
        ];

        foreach ($schedules as $schedule) {
            DB::table('visit_schedules')->insert($schedule);
        }
    }
}
