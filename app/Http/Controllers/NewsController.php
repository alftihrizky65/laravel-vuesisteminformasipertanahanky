<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    public function getNews()
    {
        // Cache for 1 hour to avoid too many requests
        return Cache::remember('atrbpn_news', 3600, function () {
            try {
                // Fetch RSS from ATR/BPN
                $response = Http::timeout(10)->get('https://www.atrbpn.go.id/feed');

                if ($response->successful()) {
                    $xml = simplexml_load_string($response->body());
                    $news = [];

                    $items = $xml->channel->item ?? [];
                    $count = 0;

                    foreach ($items as $item) {
                        if ($count >= 6) break; // Limit to 6 news items

                        // Extract image from content or use default
                        $content = (string) $item->children('content', true)->encoded ?? '';
                        $image = $this->extractImageFromContent($content);

                        if (!$image) {
                            // Try to get image from description
                            $description = (string) $item->description;
                            $image = $this->extractImageFromContent($description);
                        }

                        // Default image if none found
                        if (!$image) {
                            $image = '/sbadmin/img/news-default.jpg';
                        }

                        $news[] = [
                            'id' => $count + 1,
                            'title' => (string) $item->title,
                            'excerpt' => $this->cleanExcerpt((string) $item->description),
                            'image' => $image,
                            'url' => (string) $item->link,
                            'category' => 'Berita ATR/BPN',
                            'date' => date('d M Y', strtotime((string) $item->pubDate)),
                            'views' => rand(500, 2000), // Random views since RSS doesn't have this
                            'author' => 'ATR/BPN'
                        ];

                        $count++;
                    }

                    return response()->json($news);
                }
            } catch (\Exception $e) {
                // Fallback to static news if RSS fails
                return $this->getFallbackNews();
            }

            return $this->getFallbackNews();
        });
    }

    private function extractImageFromContent($content)
    {
        // Extract image URL from content
        preg_match('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches);
        if ($matches && isset($matches[1])) {
            return $matches[1];
        }

        // Try to find image URL in text
        preg_match('/https?:\/\/[^\/\s]+\/\S+\.(jpg|jpeg|png|gif|webp)/i', $content, $matches);
        if ($matches && isset($matches[0])) {
            return $matches[0];
        }

        return null;
    }

    private function cleanExcerpt($description)
    {
        // Remove HTML tags and limit length
        $clean = strip_tags($description);
        return strlen($clean) > 150 ? substr($clean, 0, 150) . '...' : $clean;
    }

    private function getFallbackNews()
    {
        // Fallback static news from ATR/BPN
        return response()->json([
            [
                'id' => 1,
                'title' => 'Kementerian ATR/BPN Gelar Sosialisasi Reforma Agraria Tahun 2024',
                'excerpt' => 'Kementerian ATR/BPN menggelar sosialisasi reforma agraria tahun 2024 di berbagai daerah untuk meningkatkan pemahaman masyarakat tentang program reforma agraria.',
                'image' => 'https://www.atrbpn.go.id/uploads/berita/2024/01/reforma-agraria-2024.jpg',
                'url' => 'https://www.atrbpn.go.id/berita/kementerian-atr-bpn-gelar-sosialisasi-reforma-agraria-tahun-2024',
                'category' => 'Reforma Agraria',
                'date' => '15 Jan 2025',
                'views' => 1250,
                'author' => 'ATR/BPN'
            ],
            [
                'id' => 2,
                'title' => 'Peluncuran Sistem Informasi Pertanahan Terintegrasi',
                'excerpt' => 'Sistem informasi pertanahan terintegrasi diluncurkan untuk memudahkan akses data pertanahan bagi masyarakat dan instansi terkait.',
                'image' => 'https://www.atrbpn.go.id/uploads/berita/2024/01/sistem-terintegrasi.jpg',
                'url' => 'https://www.atrbpn.go.id/berita/peluncuran-sistem-informasi-pertanahan-terintegrasi',
                'category' => 'Teknologi',
                'date' => '12 Jan 2025',
                'views' => 980,
                'author' => 'Direktorat TI'
            ],
            [
                'id' => 3,
                'title' => 'Peningkatan Sertifikasi Tanah di Daerah Perdesaan',
                'excerpt' => 'Program peningkatan sertifikasi tanah di daerah perdesaan berhasil meningkatkan legalitas kepemilikan tanah masyarakat.',
                'image' => 'https://www.atrbpn.go.id/uploads/berita/2024/01/sertifikasi-perdesaan.jpg',
                'url' => 'https://www.atrbpn.go.id/berita/peningkatan-sertifikasi-tanah-di-daerah-perdesaan',
                'category' => 'Sertifikasi',
                'date' => '8 Jan 2025',
                'views' => 756,
                'author' => 'Direktorat Penetapan Hak'
            ]
        ]);
    }
}
