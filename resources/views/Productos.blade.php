<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f0f2f5; padding: 24px; color: #333; }

        h1, h2 { margin-bottom: 16px; }

        .layout { display: flex; gap: 24px; align-items: flex-start; }

        /* Tabla productos */
        .seccion-productos { flex: 1; }

        table {
            width: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            border-collapse: collapse;
        }

        th {
            background: #4f46e5;
            color: white;
            padding: 12px 16px;
            text-align: left;
            font-size: 14px;
        }

        th:first-child { border-radius: 8px 0 0 0; }
        th:last-child { border-radius: 0 8px 0 0; }

        td { padding: 10px 16px; font-size: 14px; border-bottom: 1px solid #f0f2f5; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafafa; }

        td button {
            padding: 5px 12px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
        }
        td button:hover { background: #4338ca; }

        /* Carrito */
        .seccion-carrito {
            width: 300px;
            background: white;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            position: sticky;
            top: 24px;
        }

        .seccion-carrito h2 { border-bottom: 2px solid #f0f2f5; padding-bottom: 10px; }

        .carrito-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f2f5;
            font-size: 14px;
        }

        .carrito-item span { color: #888; font-size: 13px; }

        .carrito-item button {
            padding: 3px 8px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .carrito-item button:hover { background: #dc2626; }

        .carrito-total {
            margin-top: 12px;
            font-size: 15px;
            font-weight: bold;
            text-align: right;
            color: #4f46e5;
        }

        .carrito-vacio { font-size: 13px; color: #aaa; padding: 12px 0; text-align: center; }
    </style>
</head>
<body>

    <div class="layout">

        <div class="seccion-productos">
            <h1>Productos</h1>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td><button onclick="agregarAlCarrito({{ $product->toJson() }})">Añadir</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="seccion-carrito">
            <h2>Carrito</h2>
            <div id="carrito-items"></div>
            <div class="carrito-total" id="carrito-total"></div>
        </div>

    </div>

    <script>
        let carrito = localStorage.getItem('carrito') ? JSON.parse(localStorage.getItem('carrito')) : [];

        function agregarAlCarrito(product) {
            let posicion = carrito.findIndex(item => item.id === product.id);
            if (posicion !== -1) {
                carrito[posicion].cantidad++;
            } else {
                product.cantidad = 1;
                carrito.push(product);
            }
            localStorage.setItem('carrito', JSON.stringify(carrito));
            mostrarCarrito();
        }

        function eliminarDelCarrito(id) {
            let posicion = carrito.findIndex(item => item.id === id);
            if (posicion !== -1) {
                if (carrito[posicion].cantidad > 1) {
                    carrito[posicion].cantidad--;
                } else {
                    carrito.splice(posicion, 1);
                }
            }
            localStorage.setItem('carrito', JSON.stringify(carrito));
            mostrarCarrito();
        }

        function mostrarCarrito() {
            let div = document.getElementById('carrito-items');
            let totalDiv = document.getElementById('carrito-total');

            if (carrito.length === 0) {
                div.innerHTML = '<p class="carrito-vacio">Vacío</p>';
                totalDiv.innerHTML = '';
                return;
            }

            div.innerHTML = '';
            let total = 0;

            carrito.forEach(item => {
                let subtotal = item.price * item.cantidad;
                total += subtotal;
                div.innerHTML += `
                    <div class="carrito-item">
                        <div>
                            <strong>${item.name}</strong><br>
                            <span>$${item.price} x ${item.cantidad}</span>
                        </div>
                        <button onclick="eliminarDelCarrito(${item.id})">Quitar</button>
                    </div>`;
            });

            totalDiv.innerHTML = `Total: $${total.toFixed(2)}`;
        }

        mostrarCarrito();
    </script>

</body>
</html>
