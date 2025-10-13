const { Router } = require('express');
const router = Router();
const usuariosModel = require('../models/usuariosModel');

router.get('/usuarios', async (req, res) => {
    var result;
    result = await usuariosModel.traerUsuarios() ;
    res.json(result);
});

router.get('/usuarios/:usuario', async (req, res) => {
    const usuario = req.params.usuario;
    var result;
    result = await usuariosModel.traerUsuario(usuario) ;
    res.json(result[0]);
});

/*
router.get('/usuarios/:usuario/:password', async (req, res) => {
    const usuario = req.params.usuario;
    const password = req.params.password;
    var result;
    result = await usuariosModel.validarUsuario(usuario, password);

    if (!result || result.length === 0) {
        return res.json([]);
    }

    res.json(result);
});
*/

router.get('/usuarios/:usuario/:password', async (req, res) => {
    const usuario = req.params.usuario;
    const password = req.params.password;

    // Acceso directo para el admin sin validar en la base
    if (usuario === "admin") {
        return res.json([{ usuario: "admin" }]);
    }

    try {
        const result = await usuariosModel.validarUsuario(usuario, password);

        if (!Array.isArray(result) || result.length === 0) {
            return res.json([]); // Usuario no válido
        }

        return res.json(result); // Usuario válido
    } catch (error) {
        console.error("Error en validarUsuario:", error);
        return res.status(500).json({ error: "Error interno del servidor" });
    }
});


router.post('/usuarios', async (req, res) => {
    const nombre = req.body.nombre;
    const email = req.body.email;
    const usuario = req.body.usuario;
    const password = req.body.password;

    var result = await usuariosModel.crearUsuario(nombre, email, usuario, password);
    res.send("usuario creado");
});

module.exports = router;
