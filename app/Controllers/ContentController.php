<?php

namespace App\Controllers;

class ContentController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ========================
    // PROMO
    // ========================
    public function promoIndex()
    {
        $data = [
            'promos' => $this->db->table('promos')
                ->orderBy('id', 'DESC')
                ->get()->getResultArray(),
        ];
        return view('dashboard/promo', $data);
    }

    public function promoStore()
    {
        $this->db->table('promos')->insert([
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'badge_text'  => $this->request->getPost('badge_text'),
            'badge_color' => $this->request->getPost('badge_color'),
            'valid_until' => $this->request->getPost('valid_until') ?: null,
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/promo')->with('success', 'Promo berhasil ditambahkan.');
    }

    public function promoUpdate($id)
    {
        $this->db->table('promos')->where('id', $id)->update([
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'badge_text'  => $this->request->getPost('badge_text'),
            'badge_color' => $this->request->getPost('badge_color'),
            'valid_until' => $this->request->getPost('valid_until') ?: null,
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/promo')->with('success', 'Promo berhasil diupdate.');
    }

    public function promoDelete($id)
    {
        $this->db->table('promos')->where('id', $id)->delete();
        return redirect()->to('/promo')->with('success', 'Promo berhasil dihapus.');
    }

    // ========================
    // NEWS
    // ========================
    public function newsIndex()
    {
        $data = [
            'news' => $this->db->table('news')
                ->orderBy('id', 'DESC')
                ->get()->getResultArray(),
        ];
        return view('dashboard/news', $data);
    }

    public function newsStore()
    {
        $image    = $this->request->getFile('image');
        $imageUrl = null;

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'img/news/', $newName);
            $imageUrl = '/img/news/' . $newName;
        }

        $title = $this->request->getPost('title');
        $slug  = url_title($title, '-', true);

        // Pastikan slug unik
        $count = $this->db->table('news')->where('slug', $slug)->countAllResults();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        $this->db->table('news')->insert([
            'title'        => $title,
            'slug'         => $slug,
            'excerpt'      => $this->request->getPost('excerpt'),
            'content'      => $this->request->getPost('content'),
            'image_url'    => $imageUrl,
            'is_published' => $this->request->getPost('is_published') ? 1 : 0,
            'published_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/news')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function newsUpdate($id)
    {
        $image    = $this->request->getFile('image');
        $existing = $this->db->table('news')->where('id', $id)->get()->getRowArray();
        $imageUrl = $existing['image_url'] ?? null;

        if ($image && $image->isValid() && !$image->hasMoved()) {
            if ($imageUrl && file_exists(FCPATH . ltrim($imageUrl, '/'))) {
                unlink(FCPATH . ltrim($imageUrl, '/'));
            }
            $newName  = $image->getRandomName();
            $image->move(FCPATH . 'img/news/', $newName);
            $imageUrl = '/img/news/' . $newName;
        }

        $this->db->table('news')->where('id', $id)->update([
            'title'        => $this->request->getPost('title'),
            'excerpt'      => $this->request->getPost('excerpt'),
            'content'      => $this->request->getPost('content'),
            'image_url'    => $imageUrl,
            'is_published' => $this->request->getPost('is_published') ? 1 : 0,
        ]);

        return redirect()->to('/news')->with('success', 'Berita berhasil diupdate.');
    }

    public function newsDelete($id)
    {
        $existing = $this->db->table('news')->where('id', $id)->get()->getRowArray();

        if ($existing && $existing['image_url'] && file_exists(FCPATH . ltrim($existing['image_url'], '/'))) {
            unlink(FCPATH . ltrim($existing['image_url'], '/'));
        }

        $this->db->table('news')->where('id', $id)->delete();

        return redirect()->to('/news')->with('success', 'Berita berhasil dihapus.');
    }
}
