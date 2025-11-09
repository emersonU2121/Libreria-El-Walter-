<!-- Modal para Errores de Stock o Validación -->
<div class="modal fade" id="modalErrorStock" tabindex="-1" aria-labelledby="modalErrorStockLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalErrorStockLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Error en la Venta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark" id="modalErrorStockMessage">
                <!-- El mensaje de error se insertará aquí por JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>