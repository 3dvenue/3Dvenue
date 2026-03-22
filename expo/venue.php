<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();
if (empty($_SESSION['expoid'])) {
    header("Location: /index.php");
    exit;
}else{
	$id = $_SESSION['expoid'];
	include_once "../config.php";
}

$logDir = __DIR__ ."/{$id}";
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
$logfile = "{$logDir}/" . 'venue.log';
$log = date('Y-m-d H:i:s') . ' ' . $_SERVER['REMOTE_ADDR'] . "\n";
file_put_contents($logfile, $log, FILE_APPEND);

    $bana = "../expo/img/bana".$id.".png";

	$sql = "SELECT * FROM venue WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();

    if (!$row) {
	    header("Location: /index.php");
	    exit;
    }

    $name = $row['name'];
    $subtitle = $row['subtitle'];
    $description = $row['description'];
    $category = $row['category'];
    $benefit = $row['benefit'];

    $sql = "SELECT 
        company,
        title,
        subtitle,
        company.
        telno AS telno,
        description,
        category,
        url,
        vid,
        id,
        exhibitors.cid AS cid,
        zip,
        prefecture,
        address1,
        address2,
        logo
     FROM exhibitors 
     JOIN company ON exhibitors.cid = company.cid 
     WHERE vid = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exhibitors = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../common/css/base.css">
<link rel="stylesheet" type="text/css" href="../expo/css/style.css">
<link rel="stylesheet" type="text/css" href="../expo/css/venue.css">
<link rel="icon" href="../favicon.ico">
<title>3D EXPO</title>
</head>
<body>
<canvas id="bg-canvas"></canvas>

<!-- <div id="bana"><img src="<?=$bana?>"></div> -->

<nav id="categories">
<div class="inner">
<ul>
<?php
    $sql = "SELECT * FROM category_summary WHERE vid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        $c_id = $row['category_id'];
        $name = $row['name'];
        $cnt = $row['cnt']; //exhibion count
?>
<li class="c<?=$cnt?>" data-id="<?=$c_id?>"><?=$row['name']?></li>
<?php } ?>
</ul>
</div>
</nav>

<div id="ev">
    <button id="down"><img src="../img/down.svg?t=1"></button>
    <button id="right"><img src="../img/left.svg?t=1"></button>
    <button id="stop"><img src="../img/stop.svg?t=1"></button>
    <button id="left"><img src="../img/right.svg?t=1"></button>
    <button id="up"><img src="../img/up.svg?t=1"></button>
</div>

<div id="booth">
    <div id="boothBox">
        <div class="close">&times;</div>
        <div id="boothheader">
            <div id="logo"><img src="" alt=""></div>
            <div id="companyname"></div>
        </div>

        <div id="boothmain">
            <h2 id="title">キャッチコピー</h2>
            <figure><img id="image" src="" alt="LOGO"></figure>
            <h3 id="subtitle">サブタイトル</h3>
            <div id="btnBox"><a href="" id="url" class="btn" target="_blank">Dive in</a></div>
            <div id="description">
                <div class="boothinner">
                    <h2>Exhibition Overview</h2>
                    <p></p>
                </div>
            </div>
        </div>

        <div id="boothfooter">
            <div class="boothinner">
                <p id="company">Company</p>
                <p id="zip">Postal Code: <span></span></p>
                <p id="address">Address</p>
                <p id="telno">Tel: <span></span></p>
            </div>
        </div>
    </div>
</div>

<script src="../common/js/jquery.js"></script>
<!-- Three.js 読み込み（後で中身を入れる） -->
<script type="importmap">
{
  "imports": {
    "three": "../js/three/three.module.js",
    "three/addons/": "../js/three/addons/"
  }
}
</script>

<script type="module">
import * as THREE from "three";
import { OrbitControls } from "three/addons/controls/OrbitControls.js";
import { GLTFLoader } from "three/addons/loaders/GLTFLoader.js";
import { Reflector } from 'three/addons/objects/Reflector.js';

const exhibitors = <?php echo json_encode($exhibitors, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG); ?>;
// console.log(exhibitors); // ← jQueryでここから使える

const mouse = new THREE.Vector2();
const raycaster = new THREE.Raycaster();

$(function(){

const canvas = document.getElementById('bg-canvas');
// const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
const renderer = new THREE.WebGLRenderer({
    canvas,
    antialias: true,
    alpha: true
});
renderer.setClearColor(0x000000, 0);

/* 20260227 */
renderer.setPixelRatio(window.devicePixelRatio);
renderer.setSize(window.innerWidth, window.innerHeight);


renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 1.5;

renderer.outputEncoding = THREE.sRGBEncoding;


renderer.setSize(window.innerWidth, window.innerHeight);

const scene = new THREE.Scene();

// scene.background = new THREE.Color(0xFFFFFF);

const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
// camera.position.set(0, 0.0, 2);

/* 2026/02/27 */
camera.position.set(0, 0.0, 0.1);

const controls = new OrbitControls(camera, renderer.domElement);
controls.target.set(2.0, 1.0, 0.0);
controls.update();


controls.minDistance = 1;
controls.maxDistance = 4;

// const light = new THREE.AmbientLight(0x88aaff, 4.2);
const light = new THREE.AmbientLight(0xCCCCCC, 2.5);
scene.add(light);


//  反射床追加
const circleGeo = new THREE.CircleGeometry(10, 64);
const reflector = new Reflector(circleGeo, {
    clipBias: 0.003,
    textureWidth: window.innerWidth * window.devicePixelRatio,
    textureHeight: window.innerHeight * window.devicePixelRatio,
    color: 0xffffff
});
reflector.rotation.x = -Math.PI / 2;
reflector.position.set(0, -0.5, 0);
reflector.name = "reflector";
scene.add(reflector);

//丸床追加
const circlefloor = new THREE.CircleGeometry(10, 64);
const circleMat = new THREE.MeshStandardMaterial({
    color: 0x000000,
    transparent: true,
    roughness: 0.5,
    metalness: 0.0,
    opacity: 0.7,   // 0.0〜1.0（低いほど透明）
    side: THREE.DoubleSide
});

const circle = new THREE.Mesh(circlefloor, circleMat);
circle.rotation.x = -Math.PI / 2; // 床にする
circle.position.set(0, -0.49, 0);  // Reflector と重ならないように少し浮かせる
circle.name = "floor";
scene.add(circle);



//EXPO bana add
// const texture = new THREE.TextureLoader().load('<?=$bana?>');

const loader = new THREE.TextureLoader();
loader.load('<?=$bana?>', (texture) => {

/* 20260227 */
texture.colorSpace = THREE.SRGBColorSpace;
    texture.magFilter = THREE.LinearFilter;
    texture.minFilter = THREE.LinearMipmapLinearFilter;
    texture.anisotropy = renderer.capabilities.getMaxAnisotropy();
    
    // マテリアルに適用
    omaterial.map = texture;
    omaterial.needsUpdate = true;
});

const ogeometry = new THREE.PlaneGeometry(0.8 * 1.8, 0.8);
const omaterial = new THREE.MeshBasicMaterial({ 
    // map: texture,
    transparent: true,
    opacity:0
    });
const bana = new THREE.Mesh(ogeometry, omaterial);

bana.position.set(2.0,0.05,0);
// bana.position.set(2.0, 0.05, 0.0);
bana.rotation.y = Math.PI / 2; // 横向き（90°）
bana.name = "bana";
scene.add(bana);

const radius = 2;
let floor = 0;
const heightStep = 0.9;
const panelMeshes = [];

let checkCount = 0;

function makePanel(id){

    checkCount = 0;

    panelMeshes.forEach(mesh => {
        scene.remove(mesh);
        mesh.geometry.dispose();
        mesh.material.dispose();
    }); 

    controls.reset();
    panelMeshes.length = 0;
    const count = exhibitors.filter(item => item.category == id).length;

    checkCount = count;

    const panelCount = count;
    const panelSize = 0.8;

    const filtered = exhibitors.filter(item => item.category == id);

    for (let i = 0; i < panelCount; i++) {

        const data = filtered[i];

        const loader = new THREE.TextureLoader();

        const imgid = data.cid;
        const url = '../expo/<?=$id?>/' + imgid + '.jpg';

        loader.load(url, (LogoTexture) => {
            // LogoTexture.encoding = THREE.sRGBEncoding;
        // renderer.outputColorSpace = THREE.SRGBColorSpace;
        // LogoTexture.colorSpace = THREE.sRGBEncoding;
        LogoTexture.colorSpace = THREE.SRGBColorSpace
        renderer.outputColorSpace = THREE.SRGBColorSpace;
        /* 20260227 */
        LogoTexture.magFilter = THREE.LinearFilter;
        LogoTexture.minFilter = THREE.LinearMipmapLinearFilter;
        LogoTexture.anisotropy = renderer.capabilities.getMaxAnisotropy();
        LogoTexture.needsUpdate = true;

            // ★ 画像のアスペクト比を取得
            const imgWidth = LogoTexture.image.width;
            const imgHeight = LogoTexture.image.height;
            const imgAspect = imgWidth / imgHeight;

            // ★ Plane の高さは固定、幅は画像比率で決定
            const planeHeight = panelSize;
            const planeWidth = panelSize * imgAspect;

            // ★ 画像比率に合わせた Plane を生成（歪まない）
            const geometry = new THREE.PlaneGeometry(planeWidth, planeHeight);

            const material = new THREE.MeshBasicMaterial({
                map: LogoTexture,
                transparent: true,
                opacity:0.95
            });

            const mesh = new THREE.Mesh(geometry, material);

            const angleDeg = (i % 10) * 36 - 90;
            // const angleDeg = (i % 10) * 36;
            const y = Math.floor(i / 10) * heightStep;

            if (i % 10 === 0) {
                floor++;
            }

            placePanel(mesh, angleDeg, y);
            mesh.lookAt(0, y, 0);
            // mesh.visible = false;
            mesh.name = "card"+i;
            mesh.visible = true;
            mesh.userData = {
                cid:imgid,
                y:y
            };

            mesh.scale.set(1,1,1);

            scene.add(mesh);

            panelMeshes.push(mesh); // 削除用に配列に追加

            // setTimeout(() => { mesh.visible = true; }, i * 10);

        });

    }
}


function placePanel(mesh, angleDeg, y) {
    const rad = angleDeg * Math.PI / 180;

    const x = Math.cos(rad) * radius;
    const z = Math.sin(rad) * radius;

    mesh.position.set(x, y, z);
}


window.addEventListener('resize', () => {
    renderer.setSize(window.innerWidth, window.innerHeight);
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
});

const angleToCamera = Math.atan2(camera.position.x - bana.position.x, camera.position.z - bana.position.z);

$('#categories li').on('click',function(){
    $('#categories li').removeClass('select');
    $(this).addClass('select');
    $('#ev').addClass('active');
    const id = $(this).data('id');

    camera.position.z = 0.1;
    scene.remove(bana);
    bana.geometry.dispose();
    bana.material.dispose();

    makePanel(id);

    $('#ev').removeClass('under');

    if(checkCount <= 9){
        $('#ev').addClass('under');
    }

})


let f = 0;

$('#right').on('click',function(){
    // controls.enabled = true; 
    // controls.update();

    controls.autoRotate = true;
    controls.autoRotateSpeed = 2.0; // 右回転
});

$('#left').on('click',function(){
    // controls.enabled = true; 
    // controls.update();

    controls.autoRotate = true;
    controls.autoRotateSpeed = -2.0; // 右回転
});

$('#stop').on('click',function(){
    // controls.reset();
    controls.autoRotate = false;
})

$('#up').on('click',function(){
    if(f < (floor - 1)){
        f++;
        targetY = f * heightStep; 

    }
})

$('#down').on('click',function(){
    if(f >= 1){
        f--;
        targetY = f * heightStep;
    }
})

// window.addEventListener('click', (event) => {
$(window).on('click pointerdown', (event) => {

    if (event.target.id !== 'bg-canvas') return;

    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);
    const intersects = raycaster.intersectObjects(panelMeshes, true);

    if (intersects.length > 0) {

        const obj = intersects[0].object;
        let cid = obj.userData.cid;
        cardSet(cid);
        controls.autoRotate = false;

        // Pop Up
        showPopup(obj.userData);
    }
});


let acid = "";

function cardSet(cid){

    const expo = exhibitors.find(item => item.cid == cid);
     if(expo){
        let title = expo.title;
        let subtitle = expo.subtitle;
        let description = expo.description;
        let url = expo.url;
        let telno = expo.telno;
        let company = expo.company;
        let zip = expo.zip;
        let prefecture = expo.prefecture;
        let address1 = expo.address1;
        let address2 = expo.address2;
        let logo = expo.logo;

        let logoimage = '../logo/' + cid + '.' + logo;
        let img = '../expo/<?=$id?>/' + cid + '.jpg';
        $('#logo img').attr('src',logoimage);
        $('#image').attr('src',img);
        $('#title').text(title);
        $('#subtitle').text(subtitle);
        $('#description p').text(description);
        $('#company,#companyname').text(company);
        $('#url').attr('href',url);
        $('#zip span').text(zip);
        $('#address').text(address1+", "+address2+", "+prefecture);
        $('#telno span').text(telno);
        $('#booth').addClass('active');
        acid = cid;
        sendExhibitorLog(cid);
     }    
}

function sendExhibitorLog(cid) {
    $.post(
        './click.php', { exid: cid }) 
        .fail(function () {
    });
}

$('#url').on('click',function(){
    $.post(
        './access.php', { exid: acid }) 
        .fail(function () {
    });
})

$('#booth .close').on('click',function(){
    $('#booth').removeClass('active');
})



/* FSAP (False Simple Animation Protocol) */
const fsap = {
    to: (target, vars) => {
        for (let key in vars) {
            if (key === 'duration' || key === 'ease') continue;
            target[`_f_${key}`] = vars[key];
        }
    }
};

renderer.setAnimationLoop(tick);
const clock = new THREE.Clock();
const speed = 4;

function tick() {
    if (currentDOMTarget?.closest('#ev,#categories')) { return; }

    raycaster.setFromCamera(mouse, camera);
    const intersects = raycaster.intersectObjects(panelMeshes, true);
    
    if (intersects.length > 0) {
        const obj = intersects[0].object;
        fsap.to(obj.scale, { x: 1.1, y: 1.1, z: 1.1 });
        fsap.to(obj.material, { opacity: 1.0 });
    } else {
        panelMeshes.forEach(mesh => {
            fsap.to(mesh.scale, { x: 1.0, y: 1.0, z: 1.0 });
            fsap.to(mesh.material, { opacity: 0.95 });
        });
    }

    panelMeshes.forEach(mesh => {
        const f_speed = 0.1;

        if (mesh.scale._f_x !== undefined) {
            mesh.scale.x += (mesh.scale._f_x - mesh.scale.x) * f_speed;
            mesh.scale.y += (mesh.scale._f_y - mesh.scale.y) * f_speed;
            mesh.scale.z += (mesh.scale._f_z - mesh.scale.z) * f_speed;
        }
        if (mesh.material._f_opacity !== undefined) {
            mesh.material.opacity += (mesh.material._f_opacity - mesh.material.opacity) * f_speed;
        }
    });

    renderer.render(scene, camera);
}


let currentDOMTarget = null;

window.addEventListener('mousemove', (event) => {
    currentDOMTarget = event.target;
    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;
});



function showPopup(data) {
    // console.log("POPUP:", data);
    // ここに本物のポップアップ処理を後で書く
}

function animate() {
    requestAnimationFrame(animate);

    // bana rotation
    bana.rotation.y += (angleToCamera - bana.rotation.y) * 0.02;

    // bana opacity
    if (bana.material.opacity < 1.0) {
        bana.material.opacity += (1.0 - bana.material.opacity) * 0.01;
    }
    // ------------------------

    controls.target.y = camera.position.y;
    camera.position.y = Math.max(camera.position.y, 0);
    controls.update();
    renderer.render(scene, camera);
}

animate();



}) // jquery
</script>

</body>
</html>

