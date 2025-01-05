<style>
  /* Footer base styles */
  .gradient-custom {
    background: linear-gradient(to right, rgb(4, 4, 53), rgb(8, 54, 110));
    position: relative;
    margin-top: auto;
    width: 100%;
    box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.3);
    font-family: 'Advent Pro', sans-serif;
  }

  /* Logo styles */
  .footer-logo {
    transition: transform 0.3s ease;
  }

  .footer-logo:hover {
    transform: scale(1.05);
  }

  /* Headings */
  .footer-heading {
    color: #D4ADFC;
    font-family: 'Oswald', sans-serif;
    margin-bottom: 1.5rem;
    font-size: 1.2rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
  }

  .footer-heading::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -8px;
    width: 60px;
    height: 2px;
    background: #D4ADFC;
  }

  /* Contact info */
  .footer-contact {
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
  }

  .footer-contact i {
    color: #D4ADFC;
    width: 20px;
    margin-right: 10px;
  }

  /* Copyright section */
  .copyright {
    background-color: rgba(0, 0, 0, 0.2);
    padding: 1rem 0;
    font-size: 0.9rem;
  }

  .copyright a {
    color: #D4ADFC;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .copyright a:hover {
    color: #fff;
    text-decoration: underline;
  }

  /* Responsive styles */
  @media (max-width: 768px) {
    .footer-heading {
      margin-top: 1.5rem;
      font-size: 1.1rem;
    }

    .footer-contact {
      font-size: 0.9rem;
    }

    .footer-logo img {
      max-width: 180px;
      height: auto;
    }
  }
</style>

<footer class="text-center text-lg-start text-white gradient-custom">
    <div class="p-4">
      <section>
        <div class="row">
          <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
            <a class="navbar-brand footer-logo" href="#">
              <img src="../Assets/Images/logo.png" alt="Logo" width="240" height="150" class="d-inline-block align-text-top">
            </a>
          </div>
          <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
            <h6 class="footer-heading">EMSI CENTRE</h6>
            <p class="footer-contact"><i class="fas fa-home me-2"></i> 05 lot bouizgaren, Rte de Safi, Marrakech 40000</p>
            <p class="footer-contact"><i class="fas fa-envelope me-2"></i> info@gmail.com</p>
            <p class="footer-contact"><i class="fas fa-phone me-2"></i> + 01 234 567 88</p>
          </div>
          <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
            <h6 class="footer-heading">EMSI GUELIZ</h6>
            <p class="footer-contact"><i class="fas fa-home me-2"></i> JXJP+FRX, Marrakech 40000</p>
            <p class="footer-contact"><i class="fas fa-envelope me-2"></i> info@gmail.com</p>
            <p class="footer-contact"><i class="fas fa-phone me-2"></i> + 01 234 567 88</p>
          </div>
          <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
            <h6 class="footer-heading">EMSI CENTRE</h6>
            <p class="footer-contact"><i class="fas fa-home me-2"></i> 05 lot bouizgaren, Rte de Safi, Marrakech 40000</p>
            <p class="footer-contact"><i class="fas fa-envelope me-2"></i> info@gmail.com</p>
            <p class="footer-contact"><i class="fas fa-phone me-2"></i> + 01 234 567 88</p>
          </div>
        </div>
      </section>
    </div>
    <div class="text-center p-3 copyright">
      © 2024 Copyright:
      <a href="#">bar-bou-nez-abd-kho.ma</a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>