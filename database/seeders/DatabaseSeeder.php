<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 Panitia
        $panitia = User::updateOrCreate(
            ['email' => 'panitia@workshoptix.test'],
            [
                'name' => 'Panitia WorkshopTIX',
                'password' => Hash::make('password'),
                'role' => 'panitia',
                'email_verified_at' => now(),
            ]
        );

        // 5 Peserta Utama
        $pesertaList = [
            ['name' => 'Ahmad Fauzi', 'email' => 'peserta1@workshoptix.test'],
            ['name' => 'Siti Nurhaliza', 'email' => 'peserta2@workshoptix.test'],
            ['name' => 'Budi Pratama', 'email' => 'peserta3@workshoptix.test'],
            ['name' => 'Dewi Lestari', 'email' => 'peserta4@workshoptix.test'],
            ['name' => 'Reza Rahadian', 'email' => 'peserta5@workshoptix.test'],
        ];

        $users = [];
        foreach ($pesertaList as $p) {
            $users[] = User::updateOrCreate(
                ['email' => $p['email']],
                [
                    'name' => $p['name'],
                    'password' => Hash::make('password'),
                    'role' => 'peserta',
                    'email_verified_at' => now(),
                ]
            );
        }

        // Tambah beberapa user peserta tambahan agar total registrasi mencapai 25+
        for ($i = 6; $i <= 10; $i++) {
            $users[] = User::updateOrCreate(
                ['email' => "peserta{$i}@workshoptix.test"],
                [
                    'name' => "Peserta Demo {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'peserta',
                    'email_verified_at' => now(),
                ]
            );
        }

        // 8 Workshops Realistis SMK PESAT
        $workshopsData = [
            [
                'title' => 'Mastering Laravel 11 & Modern Web Architecture',
                'slug' => 'mastering-laravel-11',
                'description' => 'Pelajari arsitektur Laravel 11 terbaru, Eloquent optimization, Service Pattern, RESTful API design, dan deployment best practices untuk siswa SMK RPL.',
                'speaker' => 'Ir. Muhammad Ridwan, S.Kom., M.Cs.',
                'location' => 'Lab Software Engineering SMK PESAT IT XPRO',
                'starts_at' => now()->addDays(7)->setTime(9, 0),
                'price' => 75000,
                'quota' => 30,
            ],
            [
                'title' => 'Building Scalable APIs with Golang & Microservices',
                'slug' => 'microservices-golang',
                'description' => 'Membedah concurrency, goroutines, REST API performance, dan containerized microservices menggunakan bahasa Go tingkat menengah.',
                'speaker' => 'Aditya Pratama, Lead Backend Engineer',
                'location' => 'Auditorium Lantai 3 SMK PESAT',
                'starts_at' => now()->addDays(10)->setTime(13, 0),
                'price' => 100000,
                'quota' => 25,
            ],
            [
                'title' => 'Docker & Cloud Infrastructure for Beginners',
                'slug' => 'cloud-docker-devops',
                'description' => 'Praktik langsung membuat Dockerfile, Docker Compose, CI/CD pipeline dengan GitHub Actions, dan deployment ke cloud VPS.',
                'speaker' => 'Farhan Nugraha, DevOps Specialist',
                'location' => 'Lab Cloud Computing SMK PESAT',
                'starts_at' => now()->addDays(12)->setTime(8, 30),
                'price' => 85000,
                'quota' => 20,
            ],
            [
                'title' => 'Modern Frontend Mastery with Tailwind CSS v4 & Alpine.js',
                'slug' => 'modern-frontend-tailwind',
                'description' => 'Desain antarmuka web modern, interaktif, dan responsif tanpa framework berat, mengoptimalkan kecepatan muat halaman secara maksimal.',
                'speaker' => 'Dian Kusuma, UI/UX & Frontend Engineer',
                'location' => 'Lab Multimedia SMK PESAT',
                'starts_at' => now()->addDays(14)->setTime(10, 0),
                'price' => 50000,
                'quota' => 35,
            ],
            [
                'title' => 'Cybersecurity Fundamentals & Web Pentesting Bootcamp',
                'slug' => 'cybersecurity-fundamentals',
                'description' => 'Memahami celah keamanan OWASP Top 10, SQL Injection, XSS, CSRF protection, serta teknik audit keamanan aplikasi web.',
                'speaker' => 'Bambang Triatmojo, CEH, Security Analyst',
                'location' => 'Cyber Security Lab SMK PESAT',
                'starts_at' => now()->addDays(18)->setTime(9, 0),
                'price' => 120000,
                'quota' => 20,
            ],
            [
                'title' => 'Cross-Platform Mobile App with Flutter & Supabase',
                'slug' => 'flutter-mobile-development',
                'description' => 'Buat aplikasi mobile Android & iOS dalam satu basis kode menggunakan Flutter, state management Riverpod, dan backend Supabase realtime.',
                'speaker' => 'Rian Hidayat, Mobile Developer Lead',
                'location' => 'Lab RPL SMK PESAT',
                'starts_at' => now()->addDays(21)->setTime(13, 30),
                'price' => 90000,
                'quota' => 25,
            ],
            [
                'title' => 'Generative AI & LLM Integration for Web Applications',
                'slug' => 'building-llm-apps-python',
                'description' => 'Eksplorasi API Gemini, prompt engineering terstruktur, RAG (Retrieval-Augmented Generation), dan integrasi AI ke aplikasi Laravel.',
                'speaker' => 'Dr. Hendra Wijaya, AI Researcher',
                'location' => 'Ruang Teater Cyber SMK PESAT',
                'starts_at' => now()->addDays(25)->setTime(9, 0),
                'price' => 150000,
                'quota' => 30,
            ],
            [
                'title' => 'High Performance MySQL Database Optimization & Indexing',
                'slug' => 'mysql-performance-tuning',
                'description' => 'Bedah query execution plan, optimalisasi index, mengatasi query slow log, dan mencegah N+1 problem di database berskala besar.',
                'speaker' => 'Gita Permana, Senior Database Administrator',
                'location' => 'Lab Database SMK PESAT',
                'starts_at' => now()->addDays(28)->setTime(10, 0),
                'price' => 60000,
                'quota' => 25,
            ],
        ];

        $workshops = [];
        foreach ($workshopsData as $data) {
            $workshops[] = Workshop::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // 25+ Registrations in mixed statuses (pending, paid, failed, expired)
        // Unique user_id and workshop_id pairs
        $statuses = [
            'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', 'paid', // 10 paid
            'pending', 'pending', 'pending', 'pending', 'pending', 'pending', 'pending', 'pending', // 8 pending
            'failed', 'failed', 'failed', 'failed', // 4 failed
            'expired', 'expired', 'expired', 'expired', // 4 expired
        ];

        $statusIndex = 0;
        $orderCounter = 1000;

        foreach ($users as $uIndex => $user) {
            foreach ($workshops as $wIndex => $workshop) {
                if ($statusIndex >= count($statuses)) {
                    break 2;
                }

                // Pick a deterministic yet well-distributed subset of pairings
                if (($uIndex + $wIndex) % 2 === 0 || ($uIndex * 3 + $wIndex) % 5 === 0) {
                    $status = $statuses[$statusIndex];
                    $orderCounter++;

                    $isPaid = $status === 'paid';
                    $ticketCode = $isPaid ? 'WTX-' . strtoupper(Str::random(8)) : null;
                    $paidAt = $isPaid ? now()->subHours(rand(1, 48)) : null;

                    Registration::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'workshop_id' => $workshop->id,
                        ],
                        [
                            'order_id' => "WTX-{$orderCounter}-" . time(),
                            'status' => $status,
                            'snap_token' => 'dummy-snap-token-' . Str::random(24),
                            'ticket_code' => $ticketCode,
                            'paid_at' => $paidAt,
                        ]
                    );

                    $statusIndex++;
                }
            }
        }
    }
}