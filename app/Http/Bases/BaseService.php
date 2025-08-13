<?php

namespace App\Http\Bases;

use App\Traits\FileStorage;

class BaseService
{
    use FileStorage;

     ///CONTANTES DE MENSAJES DE RESPUESTA
     const MESSAGE_CREATED_SUCCESS = 'Registro creado correctamente';
     const MESSAGE_UPDATED_SUCCESS = 'Registro actualizado correctamente';
     const MESSAGE_DELETED_SUCCESS = 'Registro eliminado correctamente';
     const MESSAGE_GETS_SUCCESS = 'Registros obtenidos correctamente';
     const MESSAGE_GET_SUCCESS = 'Registro obtenido correctamente';
 
     const MESSAGE_CREATED_FAILURE = 'Error al crear el registro';
     const MESSAGE_UPDATED_FAILURE = 'Error al actualizar el registro';
     const MESSAGE_DELETED_FAILURE = 'Error al eliminar el registro';
     const MESSAGE_GETS_FAILURE = 'Error al obtener los registros';
     const MESSAGE_GET_FAILURE = 'Error al obtener el registro';
 
     const MESSAGE_NOT_FOUND = 'Registro no encontrado';
     const MESSAGE_NOT_FOUND_FAILURE = 'Error al obtener el registro';
     const MESSAGE_IN_OPERATION = 'Error al realizar la operación';
 
     const MESSAGE_REGISTER_NOT_ACTIVE = 'El préstamo no está activo';
     const MESSAGE_REGISTER_NOT_ENOUGH_AMOUNT = 'El monto a pagar es mayor a la deuda';
     
}
