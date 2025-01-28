<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateModuleCommand extends Command
{
    /**
     * El nombre y la firma del comando.
     *
     * Ejemplo de uso:
     *   php artisan create:module City --types=c,s,r
     *   php artisan create:module Cities --types=all
     */
    protected $signature = 'create:module 
                            {name : Nombre del módulo (ej: City, Cities, DocumentType, DocumentTypes)} 
                            {--types= : Tipos de clases a crear (c=controller, m=model, s=service, r=repository, q=request, all=todos)}';

    /**
     * La descripción del comando.
     */
    protected $description = 'Crea un nuevo módulo en app/Http/Modules/<PluralName> con subcarpetas y archivos base.';

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
        $inputName = $this->argument('name');
        $typesOption = $this->option('types');

        // Convertir el nombre del módulo a singular y StudlyCase
        $singularName = Str::studly(Str::singular($inputName));

        // Convertir el nombre del módulo a plural y StudlyCase
        $pluralName = Str::pluralStudly($singularName);

        // Ruta base donde se creará el módulo
        $basePath = app_path("Http/Modules/{$pluralName}");

        // Verificar si el módulo ya existe
        if (File::exists($basePath)) {
            $this->error("El módulo [{$pluralName}] ya existe en [Http/Modules].");
            return Command::FAILURE;
        }

        // Convertir los tipos de clase a un array
        $types = $this->normalizeTypes($typesOption);

        // Si el tipo es "all", crear todas las clases
        if (in_array('all', $types)) {
            $types = ['c', 'm', 's', 'r', 'q'];
        }

        // Crear la estructura de directorios
        $this->createDirectoryStructure($basePath);

        // Crear las clases según los tipos especificados
        foreach ($types as $type) {
            $this->createClass($type, $singularName, $pluralName, $basePath);
        }

        $this->info("Módulo [{$pluralName}] creado correctamente.");

        return Command::SUCCESS;
    }

    /**
     * Normaliza los tipos de clase (convierte abreviaciones a nombres completos).
     */
    private function normalizeTypes(?string $typesOption): array
    {
        if (empty($typesOption)) {
            return ['all']; // Por defecto, crear todas las clases
        }

        $types = explode(',', $typesOption);
        return array_map(function ($type) {
            return trim($type);
        }, $types);
    }

    /**
     * Crea la estructura de directorios base dentro de la carpeta del módulo.
     */
    private function createDirectoryStructure(string $basePath): void
    {
        $directories = [
            'Controllers',
            'Models',
            'Repositories',
            'Services',
            'Requests',
        ];

        foreach ($directories as $dir) {
            File::makeDirectory("{$basePath}/{$dir}", 0755, true);
        }
    }

    /**
     * Crea una clase según el tipo.
     */
    private function createClass(string $type, string $singularName, string $pluralName, string $basePath): void
    {
        switch ($type) {
            case 'c':
                $this->createController($singularName, $pluralName, $basePath);
                break;
            case 'm':
                $this->createModel($singularName, $pluralName, $basePath);
                break;
            case 's':
                $this->createService($singularName, $pluralName, $basePath);
                break;
            case 'r':
                $this->createRepository($singularName, $pluralName, $basePath);
                break;
            case 'q':
                $this->createRequest($singularName, $pluralName, $basePath);
                break;
        }
    }

    /**
     * Crea un controlador.
     */
    private function createController(string $singularName, string $pluralName, string $basePath): void
    {
        $controllerName = "{$singularName}Controller";
        $namespace = "App\\Http\\Modules\\{$pluralName}\\Controllers";

        $repositoryName = "{$singularName}Repository";
        $varRepository = lcfirst($singularName) . 'Repository';

        $serviceName = "{$singularName}Service";
        $varService = lcfirst($singularName) . 'Service';

        $stub = <<<EOT
<?php

namespace $namespace;

use App\Http\Bases\BaseController;
use App\Http\Modules\\$pluralName\Repositories\\$repositoryName;
use App\Http\Modules\\$pluralName\Services\\$serviceName;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class $controllerName extends BaseController
{
    /**
     * $controllerName constructor.
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

        $filePath = "{$basePath}/Controllers/{$controllerName}.php";
        File::put($filePath, $stub);
    }

    /**
     * Crea un modelo.
     */
    private function createModel(string $singularName, string $pluralName, string $basePath): void
    {
        $modelName = $singularName;
        $namespace = "App\\Http\\Modules\\{$pluralName}\\Models";

        $tableName = Str::snake($pluralName); // document_types

        $stub = <<<EOT
<?php

namespace $namespace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Bases\BaseModel;

class $modelName extends BaseModel
{
    use HasFactory;

    protected \$table = '$tableName';
}
EOT;

        $filePath = "{$basePath}/Models/{$modelName}.php";
        File::put($filePath, $stub);
    }

    /**
     * Crea un servicio.
     */
    private function createService(string $singularName, string $pluralName, string $basePath): void
    {
        $serviceName = "{$singularName}Service";
        $namespace = "App\\Http\\Modules\\{$pluralName}\\Services";

        $repositoryName = "{$singularName}Repository";
        $varRepository = lcfirst($singularName) . 'Repository';

        $stub = <<<EOT
<?php

namespace $namespace;

use App\Http\Modules\\$pluralName\Repositories\\$repositoryName;
USE App\Http\Bases\BaseService;

class $serviceName extends BaseService
{
    /**
     * $serviceName constructor.
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

        $filePath = "{$basePath}/Services/{$serviceName}.php";
        File::put($filePath, $stub);
    }

    /**
     * Crea un repositorio.
     */
    private function createRepository(string $singularName, string $pluralName, string $basePath): void
    {
        $repositoryName = "{$singularName}Repository";
        $namespace = "App\\Http\\Modules\\{$pluralName}\\Repositories";

        $modelName = $singularName;
        $varModel = lcfirst($modelName);

        $stub = <<<EOT
<?php

namespace $namespace;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\\$pluralName\Models\\$modelName;

class $repositoryName extends BaseRepository
{
    /**
     * $repositoryName constructor.
     *
     * @param  $modelName \$$varModel
     */
    public function __construct(protected $modelName \$$varModel)
    {
        parent::__construct(\$$varModel);
    }
}
EOT;

        $filePath = "{$basePath}/Repositories/{$repositoryName}.php";
        File::put($filePath, $stub);
    }

    /**
     * Crea un Form Request personalizado que hereda de BaseFormRequest.
     */
    private function createRequest(string $singularName, string $pluralName, string $basePath): void
    {
        $requestName = "{$singularName}Request";
        $namespace = "App\\Http\\Modules\\{$pluralName}\\Requests";

        $stub = <<<EOT
<?php

namespace $namespace;

use App\Http\Bases\BaseFormRequest;

class $requestName extends BaseFormRequest
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

        $filePath = "{$basePath}/Requests/{$requestName}.php";
        File::put($filePath, $stub);
    }
}
