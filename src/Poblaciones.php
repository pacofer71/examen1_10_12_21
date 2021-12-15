<?php

namespace Examen;

use Faker;
use PDOException;
use PDO;

class Poblaciones extends Conexion
{
    private int $id;
    private string $nombre;
    private string $descripcion;
    private string $imagen;
    private int $poblacion;
    private int $provincia_id;

    public function __construct()
    {
        parent::__construct();
    }

    //------------------------------------------------------------------------------------------------------------------
    public function create()
    {
        $q = "insert into poblaciones(nombre, descripcion, imagen, poblacion, provincia_id) values(:n, :d, :i, :p, :pi)";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->descripcion,
                ':i' => $this->imagen,
                ':p' => $this->poblacion,
                ':pi' => $this->provincia_id
            ]);
        } catch (PDOException $ex) {
            die("Error al crear: " . $ex->getMessage());
        }
        parent::$conexion = null;

    }

    public function read()
    {
        $q = (! isset($this->provincia_id)) 
        ? "select poblaciones.*, provincias.nombre as pnombre from poblaciones, provincias where provincias.id=provincia_id order by pnombre, nombre" 
        : "select poblaciones.*, provincias.nombre as pnombre from poblaciones, provincias  where provincia_id={$this->provincia_id} AND provincias.id=provincia_id order by pnombre, nombre";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error al recuperar poblaciones:  " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt;
    }

    public function read1($id)
    {
        $q = "select poblaciones.*, provincias.nombre as pnombre from poblaciones, provincias  where poblaciones.id=:id AND provincias.id=provincia_id";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':id' => $id
            ]);
        } catch (PDOException $ex) {
            die("Error al recuperar poblaciones:  " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function delete($id){
        $q="delete from poblaciones where id=:id";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':id' => $id
            ]);
        } catch (PDOException $ex) {
            die("Error al borrar poblaciones:  " . $ex->getMessage());
        }
        parent::$conexion = null;
    }
    public function update($id){
        $q = "update poblaciones set nombre=:n, descripcion=:d, imagen=:i, poblacion=:p, provincia_id=:pi where id = :id";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->descripcion,
                ':i' => $this->imagen,
                ':p' => $this->poblacion,
                ':pi' => $this->provincia_id,
                ':id'=>$id
            ]);
        } catch (PDOException $ex) {
            die("Error al actualizar: " . $ex->getMessage());
        }
        parent::$conexion = null;

    }
    //------------------------------------------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------

    public function hayPoblaciones(): bool
    {
        $q = "select id from poblaciones";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error al ver si hay poblacion:  " . $ex->getMessage());
        }
        parent::$conexion = null;
        return ($stmt->rowCount() != 0);

    }

    //------------------------------------------------------------------------------------------------------------------

    public function crearPoblciones(int $cant)
    {
        if (!$this->hayPoblaciones()) {
            $ids = (new Provincias())->provinciasIds();
            $faker = Faker\Factory::create('es_ES');
            for ($i = 0; $i < $cant; $i++) {
                (new Poblaciones)->setNombre($faker->unique()->city)
                    ->setDescripcion($faker->text(200))
                    ->setImagen('/img/poblaciones/default.jpg')
                    ->setPoblacion($faker->numberBetween(1500, 2450000000))
                    ->setProvinciaId($faker->randomElement($ids))
                    ->create();
            }
        }
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @param int $id
     * @return Poblaciones
     */
    public function setId(int $id): Poblaciones
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $nombre
     * @return Poblaciones
     */
    public function setNombre(string $nombre): Poblaciones
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @param string $descripcion
     * @return Poblaciones
     */
    public function setDescripcion(string $descripcion): Poblaciones
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * @param string $imagen
     * @return Poblaciones
     */
    public function setImagen(string $imagen): Poblaciones
    {
        $this->imagen = $imagen;
        return $this;
    }

    /**
     * @param int $poblacion
     * @return Poblaciones
     */
    public function setPoblacion(int $poblacion): Poblaciones
    {
        $this->poblacion = $poblacion;
        return $this;
    }

    /**
     * @param int $provincia_id
     * @return Poblaciones
     */
    public function setProvinciaId(int $provincia_id): Poblaciones
    {
        $this->provincia_id = $provincia_id;
        return $this;
    }


}