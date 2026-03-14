<!-- Modal for Magic Link Verification -->
<div id="magicVerificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 {{ session('show_magic_verification_popup') ? '' : 'hidden' }}">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Verifikasi Tambahan</h2>
            <p class="text-gray-600 mt-2">Harap lengkapi data berikut untuk melanjutkan login</p>
        </div>

        <form id="magicVerificationForm" method="POST" action="{{ route('magic.link.verify') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="user_id" value="{{ session('magic_verification_user_id') }}">

            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none"
                       placeholder="Masukkan nama lengkap Anda">
            </div>

            <!-- Address Field -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                <textarea id="address" name="address" required rows="3"
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none resize-none"
                          placeholder="Masukkan alamat lengkap Anda"></textarea>
            </div>

            <!-- Location Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                <button type="button" id="getLocationBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-map-marker-alt"></i>
                    <span id="locationText">Dapatkan Lokasi</span>
                </button>
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
                <p id="locationStatus" class="text-sm text-gray-500 mt-2"></p>
            </div>

            <!-- Camera Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Verifikasi</label>
                <button type="button" id="openCameraBtn"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl transition duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-camera"></i>
                    <span>Ambil Foto</span>
                </button>
                <input type="hidden" id="photoData" name="photo">
                <div id="photoPreview" class="mt-3 hidden">
                    <img id="capturedPhoto" class="w-full h-32 object-cover rounded-xl border-2 border-gray-300">
                </div>
                <p id="cameraStatus" class="text-sm text-gray-500 mt-2"></p>
            </div>

            <!-- Submit Button -->
            <button type="button" id="submitBtn" onclick="showWarningModal()"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 rounded-xl shadow-lg transform hover:scale-105 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                Konfirmasi & Masuk
            </button>
        </form>

        <!-- Close Modal Button -->
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
</div>

<!-- Warning Modal -->
<div id="warningModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <div class="text-red-500 text-6xl mb-4">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Peringatan Sistem</h2>
        </div>

        <div class="text-center text-gray-700 space-y-4 mb-8">
            <p class="font-semibold text-red-600">Sistem mendeteksi aktivitas mencurigakan!</p>
            <p>Jika ada hal mencurigakan dalam sistem, sistem akan mengulang verifikasi kembali.</p>
            <p>Anda akan dipantau 24 jam oleh sistem keamanan.</p>
            <p class="font-bold text-red-700">Pelanggaran masuk dalam Pasal IT dan dapat dikenai konsekuensi hukum.</p>
            <p class="font-semibold">Apakah Anda menerima konsekuensi ini?</p>
        </div>

        <div class="flex space-x-4">
            <button onclick="closeWarningModal()"
                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-xl transition duration-200">
                Batal
            </button>
            <button onclick="proceedLogin()"
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-xl transition duration-200">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

<script>
let stream = null;
let locationObtained = false;
let photoTaken = false;

document.addEventListener('DOMContentLoaded', function() {
    // Show modal if session flag is set
    if ({!! json_encode(session('show_magic_verification_popup')) !!}) {
        document.getElementById('magicVerificationModal').classList.remove('hidden');
        checkFormValidity();
    }

    // Location button
    document.getElementById('getLocationBtn').addEventListener('click', getLocation);

    // Camera button
    document.getElementById('openCameraBtn').addEventListener('click', openCamera);

    // Form validation
    document.querySelectorAll('input, textarea').forEach(field => {
        field.addEventListener('input', checkFormValidity);
    });
});

function closeModal() {
    document.getElementById('magicVerificationModal').classList.add('hidden');
    // Clear session flag by redirecting or something, but for now just hide
}

function getLocation() {
    const btn = document.getElementById('getLocationBtn');
    const text = document.getElementById('locationText');
    const status = document.getElementById('locationStatus');

    if (navigator.geolocation) {
        btn.disabled = true;
        text.textContent = 'Mendapatkan lokasi...';
        status.textContent = '';

        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                text.textContent = 'Lokasi Didapatkan';
                status.textContent = `Lat: ${position.coords.latitude.toFixed(6)}, Lng: ${position.coords.longitude.toFixed(6)}`;
                locationObtained = true;
                checkFormValidity();
                btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                btn.classList.add('bg-green-600', 'hover:bg-green-700');
            },
            function(error) {
                status.textContent = 'Gagal mendapatkan lokasi: ' + error.message;
                text.textContent = 'Coba Lagi';
                btn.disabled = false;
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    } else {
        status.textContent = 'Geolokasi tidak didukung oleh browser ini.';
    }
}

function openCamera() {
    const btn = document.getElementById('openCameraBtn');
    const status = document.getElementById('cameraStatus');
    const preview = document.getElementById('photoPreview');
    const img = document.getElementById('capturedPhoto');

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(mediaStream) {
                stream = mediaStream;
                const video = document.createElement('video');
                video.srcObject = stream;
                video.play();

                // Auto capture after 3 seconds
                setTimeout(() => {
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0);
                    const dataURL = canvas.toDataURL('image/jpeg');
                    document.getElementById('photoData').value = dataURL;
                    img.src = dataURL;
                    preview.classList.remove('hidden');
                    status.textContent = 'Foto berhasil diambil!';
                    photoTaken = true;
                    checkFormValidity();

                    // Stop stream
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }, 3000);

                status.textContent = 'Kamera terbuka. Foto akan diambil otomatis dalam 3 detik...';
            })
            .catch(function(error) {
                status.textContent = 'Gagal mengakses kamera: ' + error.message;
            });
    } else {
        status.textContent = 'Kamera tidak didukung oleh browser ini.';
    }
}

function checkFormValidity() {
    const name = document.getElementById('name').value.trim();
    const address = document.getElementById('address').value.trim();
    const submitBtn = document.getElementById('submitBtn');

    if (name && address && locationObtained && photoTaken) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

function showWarningModal() {
    document.getElementById('warningModal').classList.remove('hidden');
}

function closeWarningModal() {
    document.getElementById('warningModal').classList.add('hidden');
}

function proceedLogin() {
    document.getElementById('magicVerificationForm').submit();
}
</script>
