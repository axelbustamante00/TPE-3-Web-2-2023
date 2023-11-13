# **Fonoteca API**

### - Descripción
Esta API tiene como propósito acceder a los datos **(albums y artistas)** presentes en la base de datos. Todas las respuestas de la **API** tienen el siguiente formato:
```json
{
	"data": "Respuesta del endpoint",
	"status": "Estado de la respuesta"
}
```
*Aclaraciones:* 

- Los únicos valores posibles de **"status"** son **"success"** y **"error"**.

- Ante cualquier error encontrado, todas las respuestas tendrán el siguiente formato:
    ```json
    {
        "data": "Mensaje del error",
        "status": "error"
    }
    ``` 

- La dirección base de la API es la siguiente:

    - **{ruta_serividor_apache}/TPE3**

        - *En adelante nos referiremos a la ruta del servidor como **BASE_URL.***

------------


### - Requerimientos
Contar con la base de datos descripta en el siguiente archivo:
- [*Ver archivo SQL*](./database/tpe_web_2.sql)

------------

### - Endpoints
- #### Albums

    *Cada album se listará de la siguiente manera:*
            
    ```json
        {
            "id_album": 1,
            "album_name": "Clicks Modernos",
            "release_date": "1983-11-05",
            "id_artist": 1,
            "duration": "00:33:04",
            "selected": 1,
            "artist_name": "Charly García"
        }
    ```
    #####  - GET: /albums
    - Este Endpoint devuelve la lista de albums de la base de datos dentro de **"data"**. Puede recibir distintas opciones para **filtrar** y **ordenar** la lista a través de query params.
    
    - #### Parámetros de filtrado:

        - **?selected** :
        Este parámentro recibe un **string** y devuelve una lista con todos los albums cuyo valor en la columna 'selected' coincida con el valor ingresado . 
        No se tomarán como válidos ningún otro valor que no sea **0** o **1** y en caso de que el valor ingresado sea distinto a los ya mencionados, se devolverá un mensaje de error de sintaxis.

        ```json
        Por ejemplo: 
            BASE_URL/api/albums?selected=1
        Devolverá los albumes con valor 1 en la columna selected.
        ```

        - **?artista** :
        Este parámentro también recibe un **string** y devuelve una lista con todos los albums que sean del artista especificado. Cabe destacar que el mismo debe ser ingresado **respetando mayúsculas, minúsculas y la forma de escritura que tienen las URL's para caracteres especiales**. El ejemplo más usado en el proyecto es el espacio " " que deberá ser ingresado como "%20".

        ```json
        Por ejemplo: 
            BASE_URL/api/albums?artista=León%20Gieco
        Devolverá los albumes que contengan como nombre de artista a "León Gieco".
        ```

    - #### Parámetros de ordenamiento:

        - **?sort** : Este parámetro recibe un string que **debe corresponder** con uno de los **campos de la entidad solicitada**. De no corresponder se enviará como respuesta un error al no encontrarse el campo.

        - **?order** : Este parámetro recibe también un string que puede ser "asc", "desc" o los anteriores dos en mayúscula. Si es **asc** se ordenará la lista de manera **ascendente**. De ser **desc** se ordenara **descendientemente**. En caso de enviar otro valor se devolverá un mensaje de error de sintaxis. 

        ```json
        Por ejemplo: 
            BASE_URL/api/albums?sort=duration&order=desc
        Devolverá los albumes en orden descendiente según su duración.
        ```

    ##### - GET: /albums/:ID
    - Este Endpoint devuelve el album con el ID indicado.

        ```json
        Por ejemplo: 
            BASE_URL/api/albums/1
        Devolverá el album con id_album=1.
        ```

    ##### - POST: /albums
    - Este endpoint recibe un objeto **JSON** en el body del **HTTP Request** del siguiente formato:

        ```json
        /*
        Los únicos campos necesarios para añadir o
        modificar un album son "album_name", "release_date", "id_artist" y "duration". El caso de "selected" es opcional.
        */
        {
            "album_name": "Clicks Modernos",
            "release_date": "1983-11-05",
            "id_artist": 1,
            "duration": "00:33:04",
            "selected": 1
        }
        ```
        La respuesta incluirá en **"data"** el album agregado en el formato antes mostrado.

        ***Aclaracion:***

      Será necesario conocer la tabla de artistas para saber el id de artista deseado para ingresar en "id_artist".

      A la fecha de escribir éste documento los artistas disponibles con su id son los siguientes:
      	- id_artist = 1  | artist_name = Charly García.
      	- id_artist = 2  | artist_name = León Gieco.
      	- id_artist = 7  | artist_name = Fito Páez.
      	- id_artist = 8  | artist_name = Caetano Veloso.
      	- id_artist = 11 | artist_name = Bob Dylan.
      	- id_artist = 12 | artist_name = Jimi Hendrix.
      	- id_artist = 13 | artist_name = Chico Buarque.
      	- id_artist = 14 | artist_name = Luis Alberto Spinetta.
      	- id_artist = 15 | artist_name = David Bowie.

    ##### - PUT: /albums/:ID 
    - Este endpoint recibe un objeto igual al anterior en el body y modifica el elemento con el **ID** dado en la base de datos. Devuelve el album ya modificado.

    ##### - DELETE: /albums/:ID 
    - Este endpoint elimina el album con el **ID** indicado. De realizarse correctamente, devuelve el mensaje **"El album fue borrado con éxito."**.

    -------------