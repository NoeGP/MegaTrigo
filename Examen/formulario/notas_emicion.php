<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Nota de Remisión</title>
    <link rel="stylesheet" href="../estilos/style.css">


</head>

<body>
    <h1>Generar Nota de Remisión</h1>
    <form action="../consultas/generar_nota.php" method="post">
        <fieldset>
            <legend>Datos del Cliente</legend>
            <label for="fecha_emision">Fecha de Emisión:</label>
            <input type="date" id="fecha_emision" name="fecha_emision" required><br><br>

            <label for="nombre_cliente">Nombre del Cliente:</label>
            <select id="nombre_cliente" name="nombre_cliente" required>
                <?php
                include '../conexion/conn.php';
                $sql = "SELECT id_usu, nom_usu FROM usuarios WHERE rol = 2";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['nom_usu'] . "'>" . $row['nom_usu'] . "</option>";
                }
                ?>
            </select><br><br>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion"><br><br>
        </fieldset>

        <fieldset>
            <legend>Productos</legend>
            <table id="productos">
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Importe</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="producto">
                        <td><input type="number" name="cantidad[]" min="0" required
                                oninput="calcularImporte(this.parentElement.parentElement)"></td>
                        <td>   
                                <select name="producto[]" required onchange="actualizarPrecio(this)">
                                <?php
                                include '../conexion/conn.php';
                                $sql = "SELECT id_pro, nom_pro, pre_uni_pro FROM producto";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id_pro'] . "' data-precio='" . $row['pre_uni_pro'] . "'>" . $row['nom_pro'] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td><input type="number" step="0.01" name="precio_unitario[]" min="0" readonly 
                            style="background: none; border: none; pointer-events: none;" 
                            oninput="calcularImporte(this.parentElement.parentElement)"></td>
                        <td><input type="number" step="0.01" name="importe[]" min="0" readonly></td>
                        <td><button type="button" onclick="eliminarProducto(this)">Eliminar</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="agregarProducto()">Agregar Producto</button><br><br>
        </fieldset>

        <fieldset>
            <legend>Total</legend>
            <label for="firma">Firma:</label>
            <input type="text" id="firma" name="firma"><br><br>

            <label for="total">Total:</label>
            <input type="number" step="0.01" id="total" name="total" min="0" required readonly><br><br>
        </fieldset>

        <input type="submit" value="Generar Nota">
        <!-- Add this button after the form -->
        <button onclick="window.location.href='../Ingreso.html'" type="button">
            Salir
        </button>

        <button onclick="window.location.href='gestionar_notas.php'">Volver</button>
    </form>

    <script>
        function actualizarPrecio(select) {
            const fila = select.closest('tr');
            const precioInput = fila.querySelector('input[name="precio_unitario[]"]');
            const precio = select.options[select.selectedIndex].dataset.precio;
            precioInput.value = precio;
            calcularImporte(fila);
        }
        function calcularImporte(fila) {
            const cantidad = parseFloat(fila.querySelector('input[name="cantidad[]"]').value) || 0;
            const precioUnitario = parseFloat(fila.querySelector('input[name="precio_unitario[]"]').value) || 0;
            const importeInput = fila.querySelector('input[name="importe[]"]');

            const importe = cantidad * precioUnitario;
            importeInput.value = importe.toFixed(2);

            calcularTotal();
        }

        function calcularTotal() {
            const importes = document.querySelectorAll('input[name="importe[]"]');
            let total = 0;
            importes.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total').value = total.toFixed(2);
        }

        function agregarProducto() {
            const productosTbody = document.querySelector("#productos tbody");
            const nuevoProducto = document.createElement('tr');
            nuevoProducto.classList.add('producto');
            nuevoProducto.innerHTML = `
                        <td><input type="number" name="cantidad[]" min="0" required
                                oninput="calcularImporte(this.parentElement.parentElement)"></td>
                        <td>   
                                <select name="producto[]" required onchange="actualizarPrecio(this)">
                                <?php
                                include '../conexion/conn.php';
                                $sql = "SELECT id_pro, nom_pro, pre_uni_pro FROM producto";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id_pro'] . "' data-precio='" . $row['pre_uni_pro'] . "'>" . $row['nom_pro'] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td><input type="number" step="0.01" name="precio_unitario[]" min="0" required
                                oninput="calcularImporte(this.parentElement.parentElement)"></td>
                        <td><input type="number" step="0.01" name="importe[]" min="0" readonly></td>
                        <td><button type="button" onclick="eliminarProducto(this)">Eliminar</button></td>
            `;
            productosTbody.appendChild(nuevoProducto);
        }

        function eliminarProducto(boton) {
            const productoTr = boton.closest('tr');
            if (document.querySelectorAll('#productos tbody tr').length > 1) {
                productoTr.remove();
                calcularTotal();
            } else {
                alert("Debe haber al menos un producto.");
            }
        }
    </script>
</body>

</html>