<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AddClassToModuleCommand extends Command
{
    /**
     * El nombre y la firma del comando.
     *
     * Ejemplo de uso:
     *   php artisan add:class-to-module DocumentTypes Document --type=c,s,r,m
     *   php artisan add:class-to-module DocumentTypes Document --type=all
     */
    protected $signature = 'add:class-to-module 
                            {module : Nombre del módulo en StudlyCase (ej: DocumentTypes, RolesAndPermissions)} 
                            {name : Nombre base de la clase (ej: Document, Role)} 
                            {--type= : Tipo de clase (c=controller, m=model, s=service, r=repository, q=request, all=todos)}';

    /**
     * La descripción del comando.
     */
    protected $description = 'Agrega una o más clases específicas (controlador, modelo, servicio, etc.) a un módulo existente. El nombre del módulo debe estar en StudlyCase.';

    /**
     * Mapeo de tipos abreviados a tipos completos.
     */
    protected $typeMap = [
        'c' => 'controller',
        'm' => 'model',
        's' => 'service',
        'r' => 'repository',
        'q' => 'request',
        'all' => 'all',
    ];

    /**
     * Ejecuta el comando en consola.
     */
    public function handle()
    {
        $moduleName = $this->argument('module');
        $baseName = $this->argument('name');
        $typeOption = $this->option('type');

        // Validar que el nombre del módulo esté en StudlyCase
        if (!$this->isStudlyCase($moduleName)) {
            $this->error("El nombre del módulo debe estar en StudlyCase (ej: DocumentTypes, RolesAndPermissions).");
            return Command::FAILURE;
        }

        // Convertir el tipo abreviado a tipo completo si es necesario
        $types = $this->normalizeTypes($typeOption);

        // Si el tipo es "all", ignorar los demás tipos y crear todas las clases
        if (in_array('all', $types)) {
            $types = ['c', 'm', 's', 'r', 'q'];
        }

        // Validar que los tipos de clase sean válidos
        $validTypes = array_keys($this->typeMap);
        foreach ($types as $type) {
            if (!in_array($type, $validTypes)) {
                $this->error("El tipo de clase debe ser uno de los siguientes: " . implode(', ', $validTypes));
                return Command::FAILURE;
            }
        }

        // Convertir el nombre del módulo a plural (ej: DocumentTypes)
        $pluralName = Str::pluralStudly($moduleName);

        // Ruta base del módulo
        $basePath = app_path("Http/Modules/{$pluralName}");

        // Verificar si el módulo existe
        if (!File::exists($basePath)) {
            $this->error("El módulo [{$pluralName}] no existe en [Http/Modules].");
            return Command::FAILURE;
        }

        // Crear las clases según los tipos especificados
        foreach ($types as $type) {
            $className = $this->generateClassName($baseName, $type);
            $folderName = $this->getFolderName($type);
            $folderPath = "{$basePath}/{$folderName}";

            // Crear la carpeta si no existe
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
                $this->info("Carpeta [{$folderName}] creada en el módulo [{$pluralName}].");
            }

            $filePath = "{$folderPath}/{$className}.php";

            // Verificar si la clase ya existe
            if (File::exists($filePath)) {
                $this->warn("La clase [{$className}] ya existe en el módulo [{$pluralName}]. Omitiendo...");
                continue;
            }

            // Crear la clase
            $this->createClass($type, $className, $pluralName, $basePath);
            $this->info("Clase [{$className}] creada correctamente en el módulo [{$pluralName}].");
        }

        return Command::SUCCESS;
    }

    /**
     * Normaliza los tipos de clase (convierte abreviaciones a nombres completos).
     */
    private function normalizeTypes(string $typeOption): array
    {
        $types = explode(',', $typeOption);
        return array_map(function ($type) {
            return trim($type);
        }, $types);
    }

    /**
     * Valida si el nombre del módulo está en StudlyCase.
     */
    private function isStudlyCase(string $name): bool
    {
        // Un nombre en StudlyCase debe comenzar con una letra mayúscula y no contener espacios ni guiones bajos
        return preg_match('/^[A-Z][a-zA-Z0-9]*$/', $name) === 1;
    }

    /**
     * Genera el nombre de la clase basado en el tipo.
     */
    private function generateClassName(string $baseName, string $type): string
    {
        $suffix = $this->getClassSuffix($type);

        // Si el nombre base ya contiene el sufijo, no lo agregamos nuevamente
        if (Str::endsWith($baseName, $suffix)) {
            return $baseName;
        }

        return $baseName . $suffix;
    }

    /**
     * Obtiene el sufijo de la clase según el tipo.
     */
    private function getClassSuffix(string $type): string
    {
        switch ($type) {
            case 'c':
                return 'Controller';
            case 'm':
                return '';
            case 's':
                return 'Service';
            case 'r':
                return 'Repository';
            case 'q':
                return 'Request';
            default:
                return '';
        }
    }

    /**
     * Obtiene el nombre de la carpeta según el tipo.
     */
    private function getFolderName(string $type): string
    {
        switch ($type) {
            case 'c':
                return 'Controllers';
            case 'm':
                return 'Models';
            case 's':
                return 'Services';
            case 'r':
                return 'Repositories';
            case 'q':
                return 'Requests';
            default:
                return '';
        }
    }

    /**
     * Crea una clase según el tipo.
     */
    private function createClass(string $type, string $className, string $pluralName, string $basePath): void
    {
        switch ($type) {
            case 'c':
                $this->createController($className, $pluralName, $basePath);
                break;
            case 'm':
                $this->createModel($className, $pluralName, $basePath);
                break;
            case 's':
                $this->createService($className, $pluralName, $basePath);
                break;
            case 'r':
                $this->createRepository($className, $pluralName, $basePath);
                break;
            case 'q':
                $this->createRequest($className, $pluralName, $basePath);
                break;
        }
    }

    /**
     * Crea un controlador.
     */
    private function createController(string $className, string $pluralName, string $basePath): void
    {
        $namespace = "App\\Http\\Modules\\{$pluralName}\\Controllers";

        // Nombre del repositorio y servicio
        $repositoryName = Str::replaceLast('Controller', 'Repository', $className);
        $serviceName = Str::replaceLast('Controller', 'Service', $className);

        // Variables inyectadas
        $varRepository = lcfirst($repositoryName);
        $varService = lcfirst($serviceName);

        $stub = <<<EOT
<?php

namespace $namespace;

use App\Http\Bases\BaseController;
use App\Http\Modules\\$pluralName\Repositories\\$repositoryName;
use App\Http\Modules\\$pluralName\Services\\$serviceName;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class $className extends BaseController
{
    /**
     * $className constructor.
     *
     * @param  $repositoryName \$$varRepository
     * @param  $serviceName \$$varService
     */
    public function __construct(
        protected $repositoryName \$$varRepository,
        protected $serviceName \$$varService
    ) {
        //
    }

    // Aquí puedes añadir tus métodos (index, show, store, update, destroy, etc.)
}
EOT;

        $folderPath = "{$basePath}/Controllers";
        $filePath = "{$folderPath}/{$className}.php";
        File::put($filePath, $stub);
    }

    /**
     * Crea un modelo.
     */
    private function createModel(string $className, string $pluralName, string $basePath): void
    {
        $namespace = "App\\Http\\Modules\\{$pluralName}\\Models";

        $tableName = Str::snake($pluralName); // document_types

        $stub = <<<EOT
<?php

namespace $namespace;

use App\Http\Bases\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class $className extends BaseModel
{
    use HasFactory;

    protected \$table = '$tableName';
}
EOT;

        $folderPath = "{$basePath}/Models";
        $filePath = "{$folderPath}/{$className}.php";
        File::put($filePath, $stub);
    }

    /**
     * Crea un servicio.
     */
    private function createService(string $className, string $pluralName, string $basePath): void
    {
        $namespace = "App\\Http\\Modules\\{$pluralName}\\Services";

        // Nombre del repositorio
        $repositoryName = Str::replaceLast('Service', 'Repository', $className);
        $varRepository = lcfirst($repositoryName);

        $stub = <<<EOT
<?php

namespace $namespace;

use App\Http\Bases\\BaseService;
use App\Http\Modules\\$pluralName\Repositories\\$repositoryName;

class $className extends BaseService
{
    /**
     * $className constructor.
     *
     * @param  $repositoryName \$$varRepository
     */
    public function __construct(protected $repositoryName \$$varRepository)
    {
        //
    }

    // Aquí puedes añadir métodos propios de la capa de servicio
}
EOT;

        $folderPath = "{$basePath}/Services";
        $filePath = "{$folderPath}/{$className}.php";
        File::put($filePath, $stub);
    }

    /**
     * Crea un repositorio.
     */
    private function createRepository(string $className, string $pluralName, string $basePath): void
    {
        $namespace = "App\\Http\\Modules\\{$pluralName}\\Repositories";

        // Nombre del modelo
        $modelName = Str::replaceLast('Repository', '', $className);
        $varModel = lcfirst($modelName);

        $stub = <<<EOT
<?php

namespace $namespace;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\\$pluralName\Models\\$modelName;

class $className extends BaseRepository
{
    /**
     * $className constructor.
     *
     * @param  $modelName \$$varModel
     */
    public function __construct(protected $modelName \$$varModel)
    {
        parent::__construct(\$$varModel);
    }
}
EOT;

        $folderPath = "{$basePath}/Repositories";
        $filePath = "{$folderPath}/{$className}.php";
        File::put($filePath, $stub);
    }

    /**
     * Crea un Form Request personalizado que hereda de BaseFormRequest.
     */
    private function createRequest(string $className, string $pluralName, string $basePath): void
    {
        $namespace = "App\\Http\\Modules\\{$pluralName}\\Requests";

        $stub = <<<EOT
<?php

namespace $namespace;

use App\Http\Bases\BaseFormRequest;

class $className extends BaseFormRequest
{
    /**
     * Reglas de validación para el request.
     */
    public function rules(): array
    {
        return [
            // Define tus reglas de validación aquí
        ];
    }

    /**
     * Mensajes personalizados para las reglas de validación.
     */
    public function messages(): array
    {
        return [
            // Define tus mensajes personalizados aquí
        ];
    }
}
EOT;

        $folderPath = "{$basePath}/Requests";
        $filePath = "{$folderPath}/{$className}.php";
        File::put($filePath, $stub);
    }
}