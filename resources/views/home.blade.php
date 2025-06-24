@extends('layout.template')

@section('styles')
    <style>
        .hero {
            min-height: 90vh;
            background: linear-gradient(to right, #0f0c29, #302b63, #24243e);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 2rem;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .hero .highlight {
            background: linear-gradient(90deg, #00f, #f0f, #ff0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero h2 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .hero p {
            max-width: 800px;
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: justify;
            margin: 0 auto;
            color: #f0f0f0;
        }

        .hero-buttons {
            margin-top: 30px;
        }

        .hero-buttons .btn {
            margin: 0 10px;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 30px;
            transition: 0.3s;
        }

        .hero-buttons .btn:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero h2 {
                font-size: 1.3rem;
            }

            .hero p {
                font-size: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="hero">
        <h1>WELCOME TO <span class="highlight">SIMPEL</span></h1>
        <h2>SIstem Map PElanggaran Lalu lintas yang Terintegrasi, Modern, dan Interaktif.</h2>
        <p>
            Sistem ini merupakan solusi pemetaan pelanggaran lalu lintas yang terintegrasi, modern, dan interaktif,
            dirancang untuk meningkatkan efektivitas pengawasan dan analisis data lalu lintas. Dengan memanfaatkan kombinasi
            teknologi Laravel sebagai kerangka kerja aplikasi web, PostGIS untuk pengelolaan data spasial berbasis
            PostgreSQL, serta QGIS dan GeoServer untuk visualisasi dan distribusi data geografis, sistem ini mampu
            menyajikan informasi pelanggaran lalu lintas secara real-time, akurat, dan mudah diakses.
            <br><br>
            Integrasi antar komponen ini memungkinkan pengguna untuk tidak hanya melihat titik-titik pelanggaran, tetapi
            juga menganalisis pola dan tren yang terjadi, sehingga dapat mendukung pengambilan keputusan yang lebih cepat
            dan tepat oleh pihak berwenang.
        </p>
        <div class="hero-buttons">
            <a href="{{ url('/map') }}" class="btn btn-primary">Lihat Peta</a>
            <a href="{{ url('/table') }}" class="btn btn-outline-light">Lihat Data</a>
        </div>
    </div>
@endsection
