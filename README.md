# 🏡 Ejemplo Arquitectura Hexagonal con DDD

Prototipo funcional para un módulo de reservas de espacios comunes en una comunidad de vecinos.  
Incluye backend (Symfony + PHP) y frontend (pendiente), orquestado con Docker.



## 🚀 Stack Tecnológico

- **Backend**: PHP 8.2+, Symfony 5.4+, Arquitectura Hexagonal
- **Base de datos**: MariaDB
- **Testing**: PHPUnit + TDD
- **Fixtures**: DoctrineFixturesBundle
- **Contenedores**: Docker + docker-compose
- **Frontend**: Vue 3 + TypeScript





## 🛠️ Instrucciones para levantar el entorno

### 📄 Requisitos previos

- Tener instalado [Docker](https://www.docker.com/) y [Docker Compose](https://docs.docker.com/compose/).
- Puerto `8000` (backend) y `5173` (frontend) libres.

### 1️⃣ Clonar el repositorio

```bash
git clone https://github.com/llucia1/FynkusTest.git
```
```bash
cd FynkusTest
```





### 2️⃣ Levantar los contenedores
Levanta el entorno completo con Docker:
```bash
docker-compose up -d --build
```



### 🗄️ Preparar la base de datos

Una vez que los contenedores están levantados correctamente, es necesario ejecutar las migraciones y cargar los fixtures para inicializar la base de datos con datos de prueba.

### 3️⃣ Acceder al contenedor del backend con el comando `make`:
```bash
make docker-access-backend
```

### 4️⃣ Dentro del contenedor del backend ejecutar migraciones:
```bash
php bin/console doctrine:migrations:migrate
```

### 5️⃣ Dentro del contenedor del backend cargar fixtures:
```bash
php bin/console doctrine:fixtures:load
```

### 6️⃣ Probar la aplicación

Una vez ejecutadas las migraciones y, opcionalmente, cargadas las fixtures, ya puedes acceder a la aplicación desde tu navegador o con herramientas como `curl` o Postman.

- 📄 Backend API: [http://localhost:8000](http://localhost:8000)  
  Puedes probar, por ejemplo:

  curl http://localhost:8000/api/v1/space

para obtener la lista de espacios.

    🎨 Frontend: http://localhost:5173
    Desde aquí puedes interactuar con la interfaz gráfica para gestionar reservas y ver la disponibilidad.

✅ Si ambos cargan correctamente, tu entorno está listo y funcionando.






# 📄 Diseño y Arquitectura

## 🧱 Arquitectura
El proyecto está diseñado siguiendo principios de **Arquitectura Hexagonal (Ports & Adapters)** y **Domain-Driven Design (DDD)**.  
Esto permite una separación clara de responsabilidades y facilita el mantenimiento, la escalabilidad y la testabilidad.



## 📦 Bounded Contexts
Tenemos 2  **Bounded Contexts** (BC) principales:  
- `Reservation`
- `Space`

Cada uno encapsula su propia lógica de dominio, sus entidades, repositorios e interfaces.




## 🔗 Comunicación entre Bounded Contexts
La comunicación entre los BC se realiza mediante un **Bus de Eventos**.  
Esto asegura un acoplamiento bajo y permite que los contextos se comuniquen de manera asíncrona o síncrona sin depender directamente uno del otro.



## 📬 Ejemplo de Consulta de Space desde Reservation
En el contexto `Reservation` se hace una consulta para recuperar un `Space` de la siguiente forma, utilizando el **QueryBus** y siguiendo el patrón CQRS:
```bash
php
public function getSpace(string $uuid): mixed
{
    $spaceEntityQuery = $this->queryBus->ask(new GetSpaceByUuidQueried($uuid));
    $spaceEntity = $spaceEntityQuery->get();
    if (!$spaceEntity || $spaceEntity instanceof Exception) {
        return null;
    }
    return $spaceEntity;  
}
```
## 🔷 Query
```bash
final readonly class GetSpaceByUuidQueried implements Query
{
    public function __construct(private ?string $uuid) {}

    public function uuid(): ?string {
        return $this->uuid;
    }
}
```
## 🔷 QueryHandler
```bash
#[AsMessageHandler]
final readonly class GetSpaceByUuidHandler implements QueryHandler
{
    public function __construct(private readonly ISpaceRepository $spaceService) {}

    public function __invoke(GetSpaceByUuidQueried $space): SpaceEntityResponse
    {
        try {
            $spaces = $this->spaceService->getByUuid($space->uuid());
            return new SpaceEntityResponse($spaces ?: null);
        } catch (\Exception $ex) {
            throw new SpacesNotFoundException();
        }
    }
}
```
## 🪄 Bus de Eventos

Tal y como se muestra en la estructura del código (ver imagen adjunta), se ha implementado un Bus de Eventos en el módulo Common\Domain\Bus para manejar:
```bash
    Command Bus

    Query Bus

    Event Source

        DomainEvent

        DomainEventSubscriber

        EventBus
```
Esto permite tanto la ejecución de comandos y consultas como la publicación y manejo de eventos de dominio.







## 🌐 Endpoints implementados

El backend expone los siguientes endpoints para la gestión de reservas y espacios.



### 📋 Espacios (`Space`)

#### 🔷 Obtener todos los espacios disponibles
```bash
GET /api/v1/space
```
```bash
#### 📄 Respuesta:
json
[
  {
    "uuid": "aa645f49-94f3-4c63-ab3d-0afec44ebf21",
    "name": "Pista de Padel"
  },
  {
    "uuid": "c2af2336-077d-4cb4-94de-d9537f49266b",
    "name": "Piscina"
  },
  {
    "uuid": "80bdc87c-9be2-4ba9-8457-5908f56aa1fe",
    "name": "Gimnasio"
  }
]
```

### 📋 Reservas (Reservation)
#### 🔷 Consultar disponibilidad de un espacio en una fecha
```bash
GET /api/v1/reservation/space/{spaceUuid}/vailability?date=dd/mm/yyyy
```
#### 📄 Respuesta cuando existen datos:
```bash
[
  {
    "uuid": "8faf258f-dcaf-4b38-9340-c5b0df9156ff",
    "date": "2025-07-22",
    "space": {
      "uuid": "aa645f49-94f3-4c63-ab3d-0afec44ebf21",
      "name": "Pista de Padel"
    },
    "Hour": 9,
    "status": "free"
  },
  ...
]
```

#### 📄 Respuesta cuando no hay datos:
```bash
[]
```
(En ese caso el frontend genera 13 franjas libres de 09:00 a 21:00.)


### 🔷 Crear o actualizar reservas para un espacio en una fecha
```bash
POST /api/v1/reservation
```
#### 📄 Cuerpo de la petición:
```bash
{
  "spaceUuid": "c2af2336-077d-4cb4-94de-d9537f49266b",
  "date": "28/07/2025",
  "slots": [
    { "status": "free",     "hour": 9 },
    { "status": "free",     "hour": 10 },
    { "status": "free",     "hour": 11 },
    { "status": "reserved", "hour": 12 },
    { "status": "free",     "hour": 13 },
    { "status": "free",     "hour": 14 },
    { "status": "free",     "hour": 15 },
    { "status": "free",     "hour": 16 },
    { "status": "free",     "hour": 17 },
    { "status": "free",     "hour": 18 },
    { "status": "free",     "hour": 19 },
    { "status": "free",     "hour": 20 },
    { "status": "free",     "hour": 21 }
  ]
}
```
#### 📄 Respuesta:
```bash
{
  "message": "Reservas actualizadas correctamente"
}
```


### 🔗 Notas:

    Las fechas deben enviarse siempre en formato dd/mm/yyyy.

    Los horarios son en horas enteras de 09:00 a 21:00.

    Si no existe disponibilidad para el día, el backend devuelve un array vacío y el frontend crea las 13 franjas libres para mostrar.
