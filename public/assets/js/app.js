const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
const axios = require('axios');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());

// Endpoint untuk proxy ke API AI
app.post('/api/chat', async (req, res) => {
  try {
    const { message, conversationHistory } = req.body;
    
    // System prompt untuk memberikan konteks
    const systemPrompt = `
      Anda adalah asisten virtual FutZone, tempat penyewaan lapangan futsal terbaik.
      
      Informasi tentang FutZone:
      - Lokasi: Jl. Ahmad Yani No. 123, Jakarta Selatan
      - Jam operasional: 08.00 - 23.00 setiap hari
      - Nomor WhatsApp untuk booking: 0812-3456-7890
      - Website: futzone.id
      
      Informasi Lapangan:
      - 5 lapangan futsal (Lapangan A, B, C, D, E)
      - Lapangan A, B, C: Rumput sintetis
      - Lapangan D, E: Vinyl/interlock
      - Harga sewa:
        * Senin-Jumat (08.00-17.00): Rp 150.000/jam
        * Senin-Jumat (17.00-23.00): Rp 250.000/jam
        * Sabtu-Minggu: Rp 300.000/jam
      
      Promo yang sedang berlangsung:
      1. "Happy Hour": Diskon 20% untuk booking lapangan pukul 10.00-15.00 pada hari kerja
      2. "Member Card": Diskon 10% untuk pemegang member card
      3. "Paket Bulanan": Booking 10x dalam sebulan, bayar 8x saja
      4. "Promo Ultah": Diskon 15% jika booking di hari ulang tahun (dengan KTP)
      
      Cara Booking:
      1. Online: Melalui website futzone.id atau WhatsApp
      2. Offline: Datang langsung ke tempat
      3. Pembayaran: Cash, transfer bank, QRIS
      4. Minimal DP 50% untuk mengamankan slot
      
      Fasilitas:
      - Kamar mandi & ruang ganti
      - Kantin dan area tunggu
      - Free WiFi
      - Tempat parkir luas
      - Penyewaan rompi/bib
      - Bola futsal (gratis 2 per lapangan)
      
      Jawab pertanyaan pelanggan dengan ramah, informatif, dan sesuai konteks percakapan.
      Jika ada pertanyaan di luar informasi yang tersedia, sarankan untuk menghubungi nomor WhatsApp kami.
      Selalu promosikan promo-promo yang relevan dengan pertanyaan pelanggan.
      
      Jawab dalam bahasa Indonesia yang santai dan ramah.
    `;
    
    // Siapkan pesan untuk API model AI
    const messages = [
      { role: 'system', content: systemPrompt },
      ...conversationHistory
    ];
    
    // Tentukan provider mana yang ingin digunakan
    
    // Opsi 1: Menggunakan OpenAI API
    const openaiResponse = await axios.post(
      'https://api.openai.com/v1/chat/completions',
      {
        model: 'gpt-3.5-turbo',
        messages: messages,
        temperature: 0.7,
        max_tokens: 500
      },
      {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${process.env.OPENAI_API_KEY}`
        }
      }
    );
    
    const aiResponse = openaiResponse.data.choices[0].message.content;
    
    // Opsi 2: Jika menggunakan Anthropic Claude API
    /*
    const anthropicResponse = await axios.post(
      'https://api.anthropic.com/v1/messages',
      {
        model: 'claude-3-opus-20240229',
        messages: messages,
        max_tokens: 500
      },
      {
        headers: {
          'Content-Type': 'application/json',
          'x-api-key': process.env.ANTHROPIC_API_KEY,
          'anthropic-version': '2023-06-01'
        }
      }
    );
    
    const aiResponse = anthropicResponse.data.content[0].text;
    */
    
    // Kirim respons ke frontend
    res.json({ 
      success: true, 
      message: aiResponse 
    });
    
  } catch (error) {
    console.error('Error in /api/chat:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Terjadi kesalahan saat memproses permintaan Anda.' 
    });
  }
});

// Endpoint untuk melacak ketersediaan lapangan (integrasi dengan sistem booking)
app.get('/api/availability', async (req, res) => {
  try {
    const { date } = req.query;
    
    // Di sini Anda akan terhubung ke database atau sistem booking Anda
    // Contoh respons sederhana:
    const availability = {
      date: date,
      fields: [
        { name: 'Lapangan A', slots: ['08:00-09:00', '09:00-10:00', '19:00-20:00'] },
        { name: 'Lapangan B', slots: ['15:00-16:00', '16:00-17:00', '17:00-18:00'] },
        { name: 'Lapangan C', slots: ['10:00-11:00', '11:00-12:00', '20:00-21:00'] },
        { name: 'Lapangan D', slots: ['14:00-15:00', '18:00-19:00', '21:00-22:00'] },
        { name: 'Lapangan E', slots: ['12:00-13:00', '13:00-14:00', '22:00-23:00'] }
      ]
    };
    
    res.json({ success: true, data: availability });
    
  } catch (error) {
    console.error('Error in /api/availability:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Terjadi kesalahan saat memeriksa ketersediaan lapangan.' 
    });
  }
});

// Layanan untuk menyimpan percakapan
app.post('/api/save-conversation', async (req, res) => {
  try {
    const { name, email, phone, conversationHistory } = req.body;
    
    // Simpan ke database (contoh: MongoDB)
    // Di sini Anda akan menyimpan data ke database Anda
    
    // Contoh respons
    res.json({ 
      success: true, 
      message: 'Percakapan berhasil disimpan' 
    });
    
  } catch (error) {
    console.error('Error in /api/save-conversation:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Terjadi kesalahan saat menyimpan percakapan.' 
    });
  }
});

// Endpoint untuk layanan pelanggan (perpindahan ke operator manusia)
app.post('/api/human-agent', async (req, res) => {
  try {
    const { name, email, phone, issue, conversationHistory } = req.body;
    
    // Di sini Anda akan mengirim notifikasi ke tim customer service
    // dan menambahkan permintaan ke sistem tiket
    
    // Contoh respons
    res.json({ 
      success: true, 
      ticketId: 'TKT-' + Math.floor(Math.random() * 10000),
      estimatedResponse: '15 menit'
    });
    
  } catch (error) {
    console.error('Error in /api/human-agent:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Terjadi kesalahan saat menghubungi agen manusia.' 
    });
  }
});

app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});