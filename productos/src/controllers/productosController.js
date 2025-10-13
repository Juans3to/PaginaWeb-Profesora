const { Router } = require('express');
const router = Router();
const productosModel = require('../models/productosModel');

router.get('/productos', async (req, res) => {
    const id = req.params.id;
    var result;
    result = await productosModel.traerProductos() ;
    //console.log(result);
    res.json(result);
});

router.get('/productos/:id', async (req, res) => {
    const id = req.params.id;
    var result;
    result = await productosModel.traerProducto(id) ;
    //console.log(result);
    res.json(result[0]);
});

router.put('/productos/:id', async (req, res) => {
    const id = req.params.id;
    const cantidad = req.body.cantidad;

    if (cantidad<0) {
        res.send("la cantidad no puede ser menor de cero");
        return;
    }

    var result = await productosModel.actualizarProducto(id, cantidad);
    res.send("cantidad de producto actualizado");
});

router.delete('/productos/:id', async (req, res) => {
    const id = req.params.id;
    try {
        const result = await productosModel.eliminarProducto(id);
        res.json({ success: true });
    } catch (error) {
        console.error("Error al eliminar producto:", error);
        res.status(500).json({ error: "No se pudo eliminar el producto" });
    }
});

router.post('/productos', async (req, res) => {
    const nombre = req.body.nombre;
    const precio = req.body.precio;
    const cantidad = req.body.cantidad;
    
    var result = await productosModel.crearProducto(nombre, precio, cantidad);
    res.send("producto creado");
});

module.exports = router;



