<script>
(function (global) {
    function createRoundedBoxGeometry(width, height, depth, radius = 0.22) {
        const shape = new THREE.Shape();
        const x = -width / 2;
        const y = -height / 2;
        const r = Math.min(radius, width / 2, height / 2);

        shape.moveTo(x + r, y);
        shape.lineTo(x + width - r, y);
        shape.quadraticCurveTo(x + width, y, x + width, y + r);
        shape.lineTo(x + width, y + height - r);
        shape.quadraticCurveTo(x + width, y + height, x + width - r, y + height);
        shape.lineTo(x + r, y + height);
        shape.quadraticCurveTo(x, y + height, x, y + height - r);
        shape.lineTo(x, y + r);
        shape.quadraticCurveTo(x, y, x + r, y);

        const extrudeSettings = {
            steps: 1,
            depth: Math.max(0.02, depth - r * 2),
            bevelEnabled: true,
            bevelThickness: r,
            bevelSize: r,
            bevelSegments: 6
        };

        const geo = new THREE.ExtrudeGeometry(shape, extrudeSettings);
        geo.center();
        return geo;
    }

    function detectVehicleKey(brandName) {
        const lower = (brandName || '').toLowerCase();
        if (lower.includes('elf') || lower.includes('isuzu')) return 'elf';
        if (lower.includes('zenix') || lower.includes('innova') || lower.includes('mpv') || lower.includes('avanza') || lower.includes('xpander')) return 'mpv';
        return 'hiace';
    }

    function createRoundedSeat(seatInfo, isDriver) {
        const group = new THREE.Group();
        group.userData = {
            seatId: seatInfo.id || null,
            seatCode: seatInfo.seat_number || seatInfo.code,
            isAvailable: seatInfo.is_available !== false,
            isDriver: isDriver
        };

        const mat = new THREE.MeshStandardMaterial({
            color: isDriver ? 0x321B1E : 0x68967B,
            roughness: 0.4,
            metalness: 0.1
        });

        const cushion = new THREE.Mesh(createRoundedBoxGeometry(0.50, 0.15, 0.50, 0.07), mat);
        cushion.position.y = 0.12;
        cushion.userData = group.userData;
        group.add(cushion);

        const backrest = new THREE.Mesh(createRoundedBoxGeometry(0.48, 0.60, 0.14, 0.07), mat);
        backrest.position.set(0, 0.43, 0.18);
        backrest.rotation.x = -0.12;
        backrest.userData = group.userData;
        group.add(backrest);

        const headrest = new THREE.Mesh(createRoundedBoxGeometry(0.30, 0.18, 0.12, 0.06), mat);
        headrest.position.set(0, 0.80, 0.22);
        headrest.userData = group.userData;
        group.add(headrest);

        group.userData.material = mat;
        return group;
    }

    function computeSeatLayoutPositions(seatsData, vehicleSpec) {
        if (!Array.isArray(seatsData) || seatsData.length === 0) {
            return vehicleSpec.defaultSeats.map(s => ({
                id: null,
                seat_number: s.code,
                is_available: !(vehicleSpec.demoOccupied || []).includes(s.code),
                x: s.x,
                z: s.z
            }));
        }

        const count = seatsData.length;
        const startZ = -vehicleSpec.length / 2 + vehicleSpec.hoodLength + 0.55;
        const endZ = vehicleSpec.length / 2 - 0.55;
        const totalRows = Math.max(2, Math.ceil((count + 1) / 2));
        const zStep = (endZ - startZ) / Math.max(1, totalRows - 1);
        const leftX = -vehicleSpec.width * 0.25;
        const rightX = vehicleSpec.width * 0.25;

        return seatsData.map((seat, idx) => {
            if (idx === 0) {
                return { ...seat, x: leftX, z: startZ };
            }
            const pairIdx = idx - 1;
            const rowIdx = Math.floor(pairIdx / 2) + 1;
            const isLeft = pairIdx % 2 === 0;
            return {
                ...seat,
                x: isLeft ? leftX : rightX,
                z: startZ + rowIdx * zStep
            };
        });
    }

    global.initLintasGoThreeStage = function (options) {
        const container = document.getElementById(options.containerId);
        if (!container || typeof THREE === 'undefined') return null;

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(42, container.clientWidth / Math.max(1, container.clientHeight), 0.1, 100);
        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
        renderer.shadowMap.enabled = true;
        container.innerHTML = '';
        container.appendChild(renderer.domElement);

        const hemiLight = new THREE.HemisphereLight(0xFFFDF7, 0x86A5BD, 1.05);
        scene.add(hemiLight);

        const keyLight = new THREE.DirectionalLight(0xFFF7E8, 1.15);
        keyLight.position.set(6, 11, 7);
        keyLight.castShadow = true;
        scene.add(keyLight);

        const fillLight = new THREE.DirectionalLight(0xD4E4EC, 0.65);
        fillLight.position.set(-6, 5, -5);
        scene.add(fillLight);

        const platform = new THREE.Mesh(
            new THREE.CylinderGeometry(4.4, 4.6, 0.18, 48),
            new THREE.MeshStandardMaterial({ color: 0xF9F4E4, roughness: 0.55, metalness: 0.08 })
        );
        platform.position.y = -0.09;
        platform.receiveShadow = true;
        scene.add(platform);

        const ring = new THREE.Mesh(
            new THREE.TorusGeometry(4.35, 0.04, 16, 64),
            new THREE.MeshStandardMaterial({ color: 0xC78F76, roughness: 0.35 })
        );
        ring.rotation.x = Math.PI / 2;
        ring.position.y = 0.01;
        scene.add(ring);

        const vehicleRoot = new THREE.Group();
        scene.add(vehicleRoot);

        const specs = {
            hiace: {
                name: 'Toyota HiAce Commuter',
                width: 2.35, height: 1.55, length: 4.9,
                bodyColor: 0xFDFBF4,
                accentColor: 0x7A1B2D,
                hoodLength: 0.85,
                demoOccupied: ['1B', '3A', '4B'],
                defaultSeats: [
                    { code: '1A', x: -0.58, z: -1.15 },
                    { code: '2A', x: -0.58, z: -0.25 }, { code: '2B', x: 0.58, z: -0.25 },
                    { code: '3A', x: -0.58, z: 0.65 }, { code: '3B', x: 0.58, z: 0.65 },
                    { code: '4A', x: -0.58, z: 1.55 }, { code: '4B', x: 0.58, z: 1.55 }
                ]
            },
            elf: {
                name: 'Isuzu Elf Long Microbus',
                width: 2.45, height: 1.68, length: 5.5,
                bodyColor: 0xD4E4EC,
                accentColor: 0x7A1B2D,
                hoodLength: 0.55,
                demoOccupied: ['1A', '2B', '4A', '5B'],
                defaultSeats: [
                    { code: '1A', x: -0.62, z: -1.45 }, { code: '1B', x: 0.62, z: -1.45 },
                    { code: '2A', x: -0.62, z: -0.65 }, { code: '2B', x: 0.62, z: -0.65 },
                    { code: '3A', x: -0.62, z: 0.15 }, { code: '3B', x: 0.62, z: 0.15 },
                    { code: '4A', x: -0.62, z: 0.95 }, { code: '4B', x: 0.62, z: 0.95 },
                    { code: '5A', x: -0.62, z: 1.75 }, { code: '5B', x: 0.62, z: 1.75 }
                ]
            },
            mpv: {
                name: 'MPV Executive (Zenix / Avanza / Xpander)',
                width: 2.15, height: 1.28, length: 4.45,
                bodyColor: 0x7A1B2D,
                accentColor: 0xC78F76,
                hoodLength: 1.15,
                demoOccupied: ['2B'],
                defaultSeats: [
                    { code: '1A', x: -0.52, z: -0.75 },
                    { code: '2A', x: -0.52, z: 0.25 }, { code: '2B', x: 0.52, z: 0.25 },
                    { code: '3A', x: -0.52, z: 1.20 }, { code: '3B', x: 0.52, z: 1.20 }
                ]
            }
        };

        let currentVehicle = options.vehicleType || detectVehicleKey(options.brandName);
        let isDoorOpen = !!options.initialDoorOpen;
        let doorSlideAnim = isDoorOpen ? 1.45 : 0;
        let slidingDoorMesh = null;
        let roofCapMesh = null;
        let seatObjects = [];
        let selectedIds = new Set(options.initialSelectedIds || []);

        let orbitYaw = 0.75;
        let orbitPitch = 0.35;
        let orbitDist = 6.2;
        const targetCamPos = new THREE.Vector3(4.6, 2.3, 4.4);
        const targetCamLook = new THREE.Vector3(0, 0.95, 0);
        const currentCamLook = new THREE.Vector3(0, 0.95, 0);

        const raycaster = new THREE.Raycaster();
        const pointerVec = new THREE.Vector2();
        let isDragging = false;
        let pointerMoved = false;
        let prevPointer = { x: 0, y: 0 };

        function updateSeatColors() {
            seatObjects.forEach(seat => {
                const mat = seat.userData.material;
                const isAvail = seat.userData.isAvailable;
                const key = seat.userData.seatId !== null ? seat.userData.seatId : seat.userData.seatCode;

                if (!isAvail) {
                    mat.color.setHex(0x9E968E);
                    mat.emissive.setHex(0x000000);
                    seat.scale.set(1, 1, 1);
                } else if (selectedIds.has(key)) {
                    mat.color.setHex(0x7A1B2D);
                    mat.emissive.setHex(0xA3243C);
                    mat.emissiveIntensity = 0.35;
                    seat.scale.set(1.07, 1.07, 1.07);
                } else {
                    mat.color.setHex(0x68967B);
                    mat.emissive.setHex(0x000000);
                    seat.scale.set(1, 1, 1);
                }
            });
        }

        function buildVehicle(type) {
            currentVehicle = type;
            while (vehicleRoot.children.length > 0) {
                vehicleRoot.remove(vehicleRoot.children[0]);
            }
            seatObjects = [];

            const cfg = specs[type] || specs.hiace;
            const bodyMat = new THREE.MeshStandardMaterial({ color: cfg.bodyColor, roughness: 0.28, metalness: 0.18 });
            const accentMat = new THREE.MeshStandardMaterial({ color: cfg.accentColor, roughness: 0.3 });

            const baseMesh = new THREE.Mesh(createRoundedBoxGeometry(cfg.width, 0.34, cfg.length, 0.16), bodyMat);
            baseMesh.position.y = 0.52;
            baseMesh.castShadow = true;
            baseMesh.receiveShadow = true;
            vehicleRoot.add(baseMesh);

            const noseMesh = new THREE.Mesh(createRoundedBoxGeometry(cfg.width * 0.96, cfg.height * 0.55, cfg.hoodLength, 0.24), bodyMat);
            noseMesh.position.set(0, 0.85, -cfg.length / 2 + cfg.hoodLength / 2 - 0.05);
            noseMesh.castShadow = true;
            vehicleRoot.add(noseMesh);

            const grille = new THREE.Mesh(
                createRoundedBoxGeometry(cfg.width * 0.65, 0.28, 0.12, 0.06),
                new THREE.MeshStandardMaterial({ color: 0x321B1E, roughness: 0.4 })
            );
            grille.position.set(0, 0.78, -cfg.length / 2 - 0.04);
            vehicleRoot.add(grille);

            const hlGeo = createRoundedBoxGeometry(0.36, 0.14, 0.12, 0.06);
            const hlMat = new THREE.MeshBasicMaterial({ color: 0xFFFBEB });
            const hlL = new THREE.Mesh(hlGeo, hlMat);
            hlL.position.set(-cfg.width * 0.34, 0.88, -cfg.length / 2 - 0.02);
            const hlR = new THREE.Mesh(hlGeo, hlMat);
            hlR.position.set(cfg.width * 0.34, 0.88, -cfg.length / 2 - 0.02);
            vehicleRoot.add(hlL, hlR);

            const leftWall = new THREE.Mesh(createRoundedBoxGeometry(0.16, cfg.height * 0.72, cfg.length - cfg.hoodLength * 0.6, 0.08), bodyMat);
            leftWall.position.set(-cfg.width / 2 + 0.08, 0.52 + cfg.height * 0.42, 0.2);
            vehicleRoot.add(leftWall);

            const leftStripe = new THREE.Mesh(createRoundedBoxGeometry(0.18, 0.14, cfg.length - cfg.hoodLength * 0.5, 0.06), accentMat);
            leftStripe.position.set(-cfg.width / 2 + 0.08, 0.82, 0.2);
            vehicleRoot.add(leftStripe);

            const backWall = new THREE.Mesh(createRoundedBoxGeometry(cfg.width, cfg.height * 0.74, 0.2, 0.1), bodyMat);
            backWall.position.set(0, 0.52 + cfg.height * 0.42, cfg.length / 2 - 0.1);
            vehicleRoot.add(backWall);

            const doorLen = (cfg.length - cfg.hoodLength) * 0.62;
            slidingDoorMesh = new THREE.Mesh(createRoundedBoxGeometry(0.14, cfg.height * 0.72, doorLen, 0.07), bodyMat);
            slidingDoorMesh.position.set(cfg.width / 2 - 0.05, 0.52 + cfg.height * 0.42, 0.05);

            const doorGlass = new THREE.Mesh(
                createRoundedBoxGeometry(0.16, cfg.height * 0.34, doorLen * 0.82, 0.08),
                new THREE.MeshStandardMaterial({ color: 0x321B1E, roughness: 0.15, metalness: 0.6 })
            );
            doorGlass.position.y = 0.18;
            slidingDoorMesh.add(doorGlass);

            const handle = new THREE.Mesh(
                createRoundedBoxGeometry(0.18, 0.06, 0.22, 0.03),
                new THREE.MeshStandardMaterial({ color: 0xC78F76, metalness: 0.7, roughness: 0.2 })
            );
            handle.position.set(0.03, -0.08, -doorLen * 0.32);
            slidingDoorMesh.add(handle);
            vehicleRoot.add(slidingDoorMesh);

            const roofMat = new THREE.MeshStandardMaterial({
                color: cfg.bodyColor,
                roughness: 0.25,
                transparent: true,
                opacity: options.transparentRoof ? 0.12 : 0.96
            });
            roofCapMesh = new THREE.Mesh(createRoundedBoxGeometry(cfg.width * 0.96, 0.16, cfg.length - cfg.hoodLength * 0.55, 0.08), roofMat);
            roofCapMesh.position.set(0, 0.52 + cfg.height * 0.82, 0.22);
            vehicleRoot.add(roofCapMesh);

            const wheelPositions = [
                [-cfg.width / 2 + 0.06, 0.36, -cfg.length / 2 + 0.75],
                [ cfg.width / 2 - 0.06, 0.36, -cfg.length / 2 + 0.75],
                [-cfg.width / 2 + 0.06, 0.36,  cfg.length / 2 - 0.75],
                [ cfg.width / 2 - 0.06, 0.36,  cfg.length / 2 - 0.75]
            ];
            wheelPositions.forEach(pos => {
                const wheelGrp = new THREE.Group();
                const tire = new THREE.Mesh(
                    new THREE.CylinderGeometry(0.36, 0.36, 0.24, 32),
                    new THREE.MeshStandardMaterial({ color: 0x321B1E, roughness: 0.75 })
                );
                tire.rotation.z = Math.PI / 2;
                const rim = new THREE.Mesh(
                    new THREE.CylinderGeometry(0.21, 0.21, 0.26, 24),
                    new THREE.MeshStandardMaterial({ color: 0xC78F76, metalness: 0.65, roughness: 0.25 })
                );
                rim.rotation.z = Math.PI / 2;
                wheelGrp.add(tire, rim);
                wheelGrp.position.set(...pos);
                vehicleRoot.add(wheelGrp);
            });

            const laidSeats = computeSeatLayoutPositions(options.seatsData, cfg);
            const driverSeat = createRoundedSeat({ seat_number: 'SOPIR', is_available: false }, true);
            driverSeat.position.set(cfg.width * 0.24, 0.68, laidSeats[0] ? laidSeats[0].z : -1.15);
            vehicleRoot.add(driverSeat);

            laidSeats.forEach(s => {
                const seatMesh = createRoundedSeat(s, false);
                seatMesh.position.set(s.x, 0.68, s.z);
                vehicleRoot.add(seatMesh);
                seatObjects.push(seatMesh);
            });

            updateSeatColors();
        }

        function setCameraPreset(preset) {
            if (preset === 'top') {
                orbitYaw = 0;
                orbitPitch = 1.48;
                orbitDist = 6.2;
            } else if (preset === 'front') {
                orbitYaw = 0;
                orbitPitch = 0.42;
                orbitDist = 5.4;
            } else if (preset === 'side') {
                orbitYaw = Math.PI / 2.2;
                orbitPitch = 0.32;
                orbitDist = 5.6;
            } else {
                orbitYaw = 0.75;
                orbitPitch = 0.35;
                orbitDist = 6.2;
            }
        }

        if (options.initialPreset) {
            setCameraPreset(options.initialPreset);
        }

        buildVehicle(currentVehicle);

        const dom = renderer.domElement;
        dom.addEventListener('pointerdown', (e) => {
            isDragging = true;
            pointerMoved = false;
            prevPointer = { x: e.clientX, y: e.clientY };
        });

        dom.addEventListener('pointermove', (e) => {
            if (!isDragging) return;
            const dx = e.clientX - prevPointer.x;
            const dy = e.clientY - prevPointer.y;
            if (Math.abs(dx) > 3 || Math.abs(dy) > 3) pointerMoved = true;
            orbitYaw -= dx * 0.008;
            orbitPitch = Math.max(0.12, Math.min(1.52, orbitPitch + dy * 0.007));
            prevPointer = { x: e.clientX, y: e.clientY };
        });

        dom.addEventListener('pointerup', (e) => {
            const wasClick = !pointerMoved;
            isDragging = false;
            if (!wasClick || !options.interactiveSeats) return;

            const rect = dom.getBoundingClientRect();
            pointerVec.x = ((e.clientX - rect.left) / rect.width) * 2 - 1;
            pointerVec.y = -((e.clientY - rect.top) / rect.height) * 2 + 1;
            raycaster.setFromCamera(pointerVec, camera);
            const hits = raycaster.intersectObjects(seatObjects, true);
            if (hits.length > 0) {
                const data = hits[0].object.userData;
                if (data && !data.isDriver && typeof options.onSeatToggle === 'function') {
                    options.onSeatToggle(data);
                }
            }
        });

        window.addEventListener('resize', () => {
            if (!container || container.clientWidth === 0) return;
            camera.aspect = container.clientWidth / Math.max(1, container.clientHeight);
            camera.updateProjectionMatrix();
            renderer.setSize(container.clientWidth, container.clientHeight);
        });

        function animate() {
            requestAnimationFrame(animate);

            const targetSlide = isDoorOpen ? 1.45 : 0;
            doorSlideAnim += (targetSlide - doorSlideAnim) * 0.08;
            if (slidingDoorMesh) {
                slidingDoorMesh.position.z = 0.05 + doorSlideAnim;
                const baseX = currentVehicle === 'elf' ? 1.18 : (currentVehicle === 'mpv' ? 1.03 : 1.13);
                slidingDoorMesh.position.x = baseX + (doorSlideAnim > 0.05 ? 0.12 : 0);
            }

            if (roofCapMesh) {
                const wantTransparent = !!options.transparentRoof || orbitPitch > 1.0;
                const targetAlpha = wantTransparent ? 0.12 : 0.96;
                roofCapMesh.material.opacity += (targetAlpha - roofCapMesh.material.opacity) * 0.1;
            }

            const camX = Math.sin(orbitYaw) * Math.cos(orbitPitch) * orbitDist;
            const camY = Math.sin(orbitPitch) * orbitDist + 0.65;
            const camZ = Math.cos(orbitYaw) * Math.cos(orbitPitch) * orbitDist;
            targetCamPos.set(camX, camY, camZ);
            camera.position.lerp(targetCamPos, 0.08);
            currentCamLook.lerp(targetCamLook, 0.1);
            camera.lookAt(currentCamLook);

            renderer.render(scene, camera);
        }

        animate();

        return {
            setDoorOpen(open) {
                isDoorOpen = !!open;
            },
            toggleDoor() {
                isDoorOpen = !isDoorOpen;
                return isDoorOpen;
            },
            isDoorOpen() {
                return isDoorOpen;
            },
            switchVehicle(type) {
                buildVehicle(type);
            },
            setCameraPreset,
            setSelectedIds(ids) {
                selectedIds = new Set(ids);
                updateSeatColors();
            },
            getVehicleName(type) {
                return (specs[type || currentVehicle] || specs.hiace).name;
            },
            setOrbit(yaw, pitch) {
                orbitYaw = yaw;
                orbitPitch = pitch;
            }
        };
    };
})(window);
</script>
