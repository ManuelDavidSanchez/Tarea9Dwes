<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TAREA 9 - DWES</title>
        <style>
            /* General Body Styles */
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f3f4f6;
                margin: 0;
                padding: 0;
                color: blue;
                font-size: 20px;
            }

            /* Contenedor principal */
            .contenedor {
                width: 40%;
                margin-top:60px;
                margin-left: 30%;
                padding: 20px;
                background-color: #ffffff;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                text-align: center;
            }

            /* Estilo para el encabezado */
            h1 {
                color: blue;
                font-size: 2rem;
                margin-bottom: 30px;
            }

            /* Estilo para el formulario */
            form {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-bottom: 30px;
            }

            /* Campo de texto */
            input[type="text"] {
                padding: 10px;
                font-size: 1rem;
                border-radius: 5px;
                border: 1px solid #ddd;
                margin-right: 10px;
                width: 300px;
                transition: border-color 0.3s ease;
            }

            input[type="text"]:focus {
                border-color: #4CAF50;
                outline: none;
            }

            /* Botón de búsqueda */
            button {
                padding: 10px 20px;
                font-size: 1rem;
                background-color: blue;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            button:hover {
                background-color: #45a049;
            }

            /* Contenedor de resultados */
            .resultadoContenedor {
                display: flex;
                justify-content: center;
                align-items: flex-start;
                gap: 30px;
                margin-top: 30px;
            }

            /* Estilo para la imagen del Pokémon */
            .pokemon-imagen img {
                width: 150px;
                height: 150px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            /* Estilo para la información del Pokémon */
            .pokemon-info {
                max-width: 300px;
                text-align: left;
                padding: 15px;
                background-color: #f9f9f9;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                
            }

            .pokemon-info p {
                font-size: 20px;
                color: #555;
                margin-bottom: 10px;
                
            }

            /* Estilo para los títulos dentro de la información */
            .pokemon-info strong {
                color: blue;
                font-weight: bold;
                font-size: 20px;
            }
        </style>
    </head>
    <body>      
        <div class="contenedor">
            <h1>Tarea 9 - Dwes</h1>
            
            <!-- Formulario para buscar Pokémon por nombre o ID -->
            <form method="GET" onsubmit="return validarFormulario()">
                <!-- Campo de entrada para el nombre o ID del Pokémon -->
                <input type="text" name="pokemon" id="pokemon" placeholder="Introduce el nombre o ID del Pokémon" required>
                <!-- Botón para enviar el formulario -->
                <button type="submit">Buscar</button>
            </form>
            
            <div class="resultadoContenedor">      
                <?php
                  /**
                    * Obtiene la URL de la API para un Pokémon.
                    *
                    * Esta función recibe el nombre o ID de un Pokémon y construye la URL 
                    * para obtener los datos de la PokeAPI.
                    *
                    * @param string $name Nombre o ID del Pokémon.
                    * @return string La URL completa para hacer la solicitud a la PokeAPI.
                    */
                   function obtenerUrlApi($name) {
                       return "https://pokeapi.co/api/v2/pokemon/" . strtolower($name) . "/";
                   }

                   /**
                    * Realiza la solicitud a la PokeAPI y obtiene los datos del Pokémon.
                    *
                    * Esta función hace la solicitud a la PokeAPI utilizando file_get_contents()
                    * y devuelve los datos en formato JSON decodificado.
                    *
                    * @param string $url La URL de la API para acceder a los datos del Pokémon.
                    * @return array|null Los datos del Pokémon decodificados, o null en caso de error.
                    */
                   function obtenerDatosDeApi($url) {
                       // Intentar obtener los datos de la API usando file_get_contents
                       $data = @file_get_contents($url);

                       if ($data === FALSE) {
                           return null; // Error al obtener los datos
                       }

                       // Decodificar el JSON y devolver el resultado
                       return json_decode($data, true);
                   }

                   /**
                    * Muestra la información del Pokémon.
                    *
                    * Esta función toma los datos del Pokémon obtenidos de la PokeAPI y los muestra
                    * en una estructura HTML, incluyendo la imagen, ID, nombre, tipo, altura y peso.
                    *
                    * @param array $pokemonData Los datos del Pokémon obtenidos de la API.
                    * @return void Esta función no retorna nada, solo genera contenido HTML.
                    */
                   function mostrarPokemon($pokemonData) {
                       echo "<div class='pokemon-imagen'>";
                       // Mostrar la imagen del Pokémon
                       $spriteUrl = $pokemonData['sprites']['front_default'];
                       echo "<p><strong>Pokemon encontrado:</strong><br><img src='$spriteUrl' alt='" . $pokemonData['name'] . "' class='pokemon-imagen'></p>";
                       echo "</div>";

                       echo "<div class='pokemon-info'>";
                       // Mostrar la información del Pokémon (ID, nombre, tipo, altura, peso)
                       echo "<p><strong>ID:</strong> " . $pokemonData['id'] . "</p>";  
                       echo "<p><strong>Nombre:</strong> " . $pokemonData['name'] . "</p>";
                       echo "<p><strong>Tipo:</strong> " . $pokemonData['types'][0]['type']['name'] . "</p>";
                       echo "<p><strong>Altura:</strong> " . $pokemonData['height'] . " dm</p>";
                       echo "<p><strong>Peso:</strong> " . $pokemonData['weight'] . " hm</p>";
                       echo "</div>";
                   }

                   // Aquí empieza el flujo principal
                   if (isset($_GET['pokemon'])) {
                       // Obtener el nombre o ID del Pokémon ingresado
                       $name = $_GET['pokemon'];

                       // Obtener la URL de la API
                       $url = obtenerUrlApi($name);

                       // Obtener los datos de la API
                       $pokemonData = obtenerDatosDeApi($url);

                       if ($pokemonData === null) {
                           echo "<p>Error: No se encontró información para el Pokémon <strong>$name</strong>.</p>";
                       } else {
                           // Mostrar los datos del Pokémon
                           mostrarPokemon($pokemonData);
                       }
                   }

                ?>
            </div>
        </div>
        <script>
            /**
            * Valida que el campo "pokemon" contenga solo letras y números antes de enviar el formulario.
            *
            * @returns {boolean} Devuelve `true` si la entrada es válida, `false` en caso contrario.
            */
            function validarFormulario() {         
                // Obtener el valor del campo de entrada del formulario
                var pokemonName = document.getElementById("pokemon").value;

                // Expresión regular para solo letras y números 
                var regex = /^[a-zA-Z0-9]+$/;

                // Si el valor del campo no cumple con la expresión regular, mostramos un alert
                if (!regex.test(pokemonName)) {
                    alert ( "Por favor, ingrese solo letras y numeros (sin caracteres especiales).");
                    return false;  // No enviar el formulario
                }

                return true;  // Permitir enviar el formulario
            }
        </script>
    </body>
</html>

