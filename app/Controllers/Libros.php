<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Libro;

class Libros extends Controller{

    public function index(){

        $libro = new Libro();
        $datos['libros']=$libro->orderBy('id','ASC')->findAll();
        
        $datos['cabecera']=view('template/cabecera');
        $datos['pie']=view('template/pie');

        return view('libros/listar',$datos);
    }
    public function crear(){

        $datos['cabecera']=view('template/cabecera');
        $datos['pie']=view('template/pie');

        return view('libros/crear', $datos);
    }
    
    public function guardar(){

        $libro= new Libro();

        $validacion=$this->validate([
            'titulo'=>'required|min_length[3]',
            'autor'=>'required|min_length[3]',
            'descripcionc'=>'required|min_length[3]',
            'descripcion'=>'required|min_length[3]',
            'isbn'=>'required|min_length[3]',
            'editorial'=>'required|min_length[3]',
            'numpag'=>'required|min_length[3]',
            'edicion'=>'required|min_length[3]',
            'pais'=>'required|min_length[3]',
            'anio'=>'required|min_length[3]',
            'imagen'=> [
                'uploaded[imagen]',
                'mime_in[imagen, image/jpg,image/jpeg,image/png]',
                'max_size[imagen,1024]',
            ]
        ]);
        if(!$validacion){
            $session=session();
            $session->setFlashdata('mensaje','Revise la información :c');

            return redirect()->back()->withInput();
        }

		if ($imagen = $this->request->getFile('imagen')) {
			$nuevoNombre = $imagen->getRandomName();
				$imagen->move('./public/uploads/', $nuevoNombre);
            $datos=[
                'titulo'=>$this->request->getVar('titulo'),
                'autor'=>$this->request->getVar('autor'),
                'descripcionc'=>$this->request->getVar('descripcionc'),
                'imagen'=>$nuevoNombre,
                'descripcion'=>$this->request->getVar('descripcion'),
                'isbn'=>$this->request->getVar('isbn'),
                'editorial'=>$this->request->getVar('editorial'),
                'numpag'=>$this->request->getVar('numpag'),
                'edicion'=>$this->request->getVar('edicion'),
                'pais'=>$this->request->getVar('pais'),
                'anio'=>$this->request->getVar('anio')
            ];
            $libro->insert($datos);

        }

        return $this->response->redirect(site_url('/listar'));
    }

    public function borrar($id=null){
        $libro =new Libro();
        $datosLibro=$libro->where('id',$id)->first();

        $ruta=('./public/uploads/'.$datosLibro['imagen']);
        unlink($ruta);
    
        $libro->where('id',$id)->delete($id);
        return $this->response->redirect(site_url('/listar'));
    }

    public function editar($id=null){

        $libro= new Libro();
        $datos['libro']=$libro->where('id',$id)->first();

        $datos['cabecera']=view('template/cabecera');
        $datos['pie']=view('template/pie');



        return view('libros/editar', $datos);
    }
    public function actualizar(){
        $libro= new Libro();

        $datos=[
            'titulo'=>$this->request->getVar('titulo'),
            'autor'=>$this->request->getVar('autor'),
            'descripcionc'=>$this->request->getVar('descripcionc'),
            'descripcion'=>$this->request->getVar('descripcion'),
            'isbn'=>$this->request->getVar('isbn'),
            'editorial'=>$this->request->getVar('editorial'),
            'numpag'=>$this->request->getVar('numpag'),
            'edicion'=>$this->request->getVar('edicion'),
            'pais'=>$this->request->getVar('pais'),
            'anio'=>$this->request->getVar('anio')
        ];
        $id=$this->request->getVar('id');
        
        $validacion=$this->validate([
            'titulo'=>'required|min_length[3]',
            'autor'=>'required|min_length[3]',
            'descripcionc'=>'required|min_length[3]',
            'descripcion'=>'required|min_length[3]',
            'isbn'=>'required|min_length[3]',
            'editorial'=>'required|min_length[3]',
            'numpag'=>'required|min_length[3]',
            'edicion'=>'required|min_length[3]',
            'pais'=>'required|min_length[3]',
            'anio'=>'required|min_length[3]'
        ]);
        if(!$validacion){
            $session=session();
            $session->setFlashdata('mensaje','Revise la información :c');

            return redirect()->back()->withInput();
        }       

        $libro->update($id,$datos);

        $validacion=$this->validate([
            'imagen'=> [
                'uploaded[imagen]',
                'mime_in[imagen, image/jpg,image/jpeg,image/png]',
                'max_size[imagen,1024]',
            ]
        ]);

        if($validacion){

            if ($imagen = $this->request->getFile('imagen')) {
                $datosLibro=$libro->where('id',$id)->first();

                $ruta=('./public/uploads/'.$datosLibro['imagen']);
                unlink($ruta);

                $nuevoNombre = $imagen->getRandomName();
                $imagen->move('./public/uploads/', $nuevoNombre);
                $datos=['imagen'=>$nuevoNombre];
                $libro->update($id,$datos);
    
            }
        }
        return $this->response->redirect(site_url('/listar'));
    }
    public function libreria(){

        $libro = new Libro();
        $datos['libros']=$libro->orderBy('id','ASC')->findAll();
        
        $datos['cabecera']=view('template/cabecera');
        $datos['pie']=view('template/pie');

        return view('libros/libreria',$datos);
    }
    public function mostrar($id = null)
	{
		$libro = new Libro();
		$datos['libro'] = $libro->where('id', $id)->first();
		$datos['cabecera'] = view('template/cabecera');
		$datos['pie'] = view('template/pie');
		return view('mostrar',$datos);
	}
}