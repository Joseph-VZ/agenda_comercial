<form id="form-nueva-subcategoria" autocomplete="off" method="POST">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Subcategoría:</label>
        <input type="text" class="form-control" name="nombre" placeholder="Ej. Tablets" maxlength="60" required>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción:</label>
        <textarea class="form-control" name="descripcion" rows="3" placeholder="Breve descripción de la subcategoría" maxlength="255" required></textarea>
    </div>

    <div class="mb-3">
        <label for="id_categoria" class="form-label">Categoría:</label>
        <select class="form-select" name="id_categoria" required>
            <option value="">Seleccione una categoría</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" id="btn-guardar-subcategoria" class="btn btn-primary">Guardar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-subcategoria">Cancelar</button>
</form>
