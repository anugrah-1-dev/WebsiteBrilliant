<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Brilliant English Course</title>
  @vite('resources/css/landingpage.css')
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

  <!-- Navbar -->
  <nav id="navbar">
    <div class="logo">
      <div class="top">BRILLIANT</div>
      <div class="bottom">ENGLISH COURSE</div>
    </div>
    <div class="nav-links">
      <a href="#">DASHBOARD</a>
      <a href="#tentang">TENTANG KAMI</a>
      <a href="#program">PROGRAM & HARGA</a>
      <a href="#">GALERI</a>
      <a href="#kontak">KONTAK</a>
      <a href="#" class="btn">PENDAFTARAN</a>
    </div>
  </nav>

  <!-- Carousel -->
  <section class="carousel" id="carousel">
    <div class="carousel-container">
      <div class="slides">
        <img src="{{ asset('asset/img/Astra.Yao.full.4397350.jpg') }}" class="slide active" alt="Slide 1">
        <img src="{{ asset('asset/img/azur-lane-enterprise-anime-girl-uhdpaper.com-4K-4.1756.jpg') }}" class="slide" alt="Slide 2">
        <img src="{{ asset('asset/img/Feixiao.full.4282475.png') }}" class="slide" alt="Slide 3">
      </div>
      <div class="overlay"></div>
      <div class="carousel-text">
        <h1>BRILLIANT ENGLISH COURSE</h1>
        <p>Tingkatkan kemampuan Bahasa Inggris Anda dan rasakan pengalaman belajar yang berkualitas di Brilliant English Course, tempat di mana potensi Anda menjadi lebih gemilang!</p>
        <a href="#daftar" class="cta-button">DAFTAR SEKARANG</a>
      </div>
      <button class="nav prev" onclick="changeSlide(-1)">&#10094;</button>
      <button class="nav next" onclick="changeSlide(1)">&#10095;</button>
    </div>
  </section>

  <!-- Tentang Kami -->
  <section class="about" id="tentang">
    <h2>TENTANG KAMI</h2>
    <p>Brilliant English Course adalah salah satu lembaga kursus unggulan di Kampung Inggris Pare yang dikenal dengan metode pembelajaran yang efektif, suasana belajar nyaman, serta fasilitas lengkap yang mendukung proses belajar Bahasa Inggris dari dasar hingga mahir.</p>
  </section>

  <!-- Divider -->
  <div class="section-divider-wrapper">
    <div class="section-divider"></div>
  </div>

  <!-- Program & Harga -->
  <section class="program" id="program">
    <h2>PROGRAM & HARGA</h2>
    <div class="program-container">
      <div class="program-col">
        <div class="program-item">
          <h3>Paket Dasar</h3>
          <p>Belajar dari nol: grammar, speaking, dan vocabulary dasar.</p>
          <span class="price">Rp 750.000 / 2 minggu</span>
        </div>
        <div class="program-item">
          <h3>Paket Intensif</h3>
          <p>Program full-day dengan jadwal padat dan fokus speaking.</p>
          <span class="price">Rp 1.200.000 / 2 minggu</span>
        </div>
      </div>
      <div class="program-col">
        <div class="program-item">
          <h3>Paket TOEFL</h3>
          <p>Persiapan ujian TOEFL dengan latihan soal dan tips jitu.</p>
          <span class="price">Rp 950.000 / 2 minggu</span>
        </div>
        <div class="program-item">
          <h3>Paket Profesional</h3>
          <p>Khusus untuk karyawan dan mahasiswa yang ingin belajar praktis.</p>
          <span class="price">Rp 1.500.000 / 2 minggu</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Kontak -->
  <section class="contact" id="kontak">
    <div class="contact-wrapper">
      <div class="contact-box">
        <i class="fas fa-map-marker-alt contact-icon"></i>
        <h4>Our Location</h4>
        <p>Jl. Kampung Inggris, Indonesia</p>
      </div>
      <div class="contact-box">
        <i class="fas fa-envelope contact-icon"></i>
        <h4>Email Us</h4>
        <p>info@brilliant.co.id</p>
      </div>
      <div class="contact-box">
        <i class="fas fa-phone contact-icon"></i>
        <h4>Call Us</h4>
        <p>+62 123 4567 8900</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    &copy;  2025 Brilliant English Course. Hak Cipta Dilindungi Oleh Undang-Undang
  </footer>

  <!-- Script -->
  <script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.remove('active');
        if (i === index) slide.classList.add('active');
      });
    }

    function changeSlide(step) {
      currentSlide = (currentSlide + step + totalSlides) % totalSlides;
      showSlide(currentSlide);
    }

    setInterval(() => {
      changeSlide(1);
    }, 5000);

    // Sticky Navbar Scroll Detection
    const navbar = document.getElementById("navbar");
    const carousel = document.getElementById("carousel");

    window.addEventListener("scroll", () => {
      const carouselBottom = carousel.offsetTop + carousel.offsetHeight;
      if (window.scrollY > carouselBottom - 80) {
        navbar.classList.add("scrolled");
      } else {
        navbar.classList.remove("scrolled");
      }
    });
  </script>

</body>
</html>
