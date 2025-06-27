<div class="container py-4">
    <h1 class="text-center mb-4">Citas</h1>

    <div class="mb-3 text-end">
        <button class="btn btn-primary me-2" id="btn-nueva-cita">
            <i class="fas fa-calendar-plus"></i> Nueva Cita
        </button>

        <?php if (!isset($_SESSION['access_token'])): ?>
            <a href="https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=offline&client_id=621062965826-tdv52eg4okgrgrpfpptjmkir9p4g4dhc.apps.googleusercontent.com&redirect_uri=http://localhost/agenda_comercial/public/callback.php&scope=https://www.googleapis.com/auth/calendar.readonly" 
              class="btn btn-outline-danger" id="btn-google-login">
                <i class="fab fa-google"></i> Conectar con Google
            </a>
        <?php else: ?>
            <form method="POST" action="/agenda_comercial/public/desconectar_google.php" style="display: inline;">
                <button type="submit" class="btn btn-outline-secondary" id="btn-google-logout">
                    <i class="fas fa-sign-out-alt"></i> Desconectar Google
                </button>
            </form>
        <?php endif; ?>

    </div>

</div>


    <!-- Calendario  -->
    <div id="contenedor-calendario">
        <div id="calendar" style="max-width: 100%;"></div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-cita" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Cita</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="modal-cita-body">

      </div>
    </div>
  </div>
</div>
