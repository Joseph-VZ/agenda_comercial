<?php if ($subcategoria): ?>
    <form id="form-editar-subcategoria" method="POST">
        <input type="hidden" name="id" value="<?= $subcategoria['id'] ?>">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Subcategoría:</label>
            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($subcategoria['nombre']) ?>" maxlength="60" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea class="form-control" name="descripcion" rows="3" maxlength="255" required><?= htmlspecialchars($subcategoria['descripcion']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="id_categoria" class="form-label">Categoría:</label>
            <select class="form-select" name="id_categoria" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>" <?= $categoria['id'] == $subcategoria['id_categoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <button type="button" class="btn btn-secondary" id="btn-cancelar-subcategoria">Cancelar</button>
    </form>
<?php else: ?>
    <p class="text-danger">Subcategoría no encontrada.</p>
<?php endif; ?>
