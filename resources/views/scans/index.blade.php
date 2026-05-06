@extends('layouts.app')

@section('title', 'Scan Absensi')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[60vh]" x-data="scanHandler()">
        <div class="relative w-full max-w-md p-8 overflow-hidden text-center bg-white border border-gray-100 shadow-2xl rounded-3xl">

            <!-- Tampilan Header & Scan (Muncul saat idle) -->
            <div x-show="status === 'idle'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100">
                <div class="flex items-center justify-center w-20 h-20 mx-auto mb-4 bg-blue-100 rounded-full">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Scan Barcode Siswa</h2>
                <p class="mt-2 text-gray-500">Silahkan arahkan barcode ke scanner</p>
            </div>

            <!-- Tampilan Hasil Scan (Foto & Info) -->
            <div x-show="status !== 'idle' && status !== 'error'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100" class="flex flex-col items-center">
                <div class="relative mb-4">
                    <img :src="studentPhoto"
                        class="object-cover w-32 h-32 border-4 border-blue-500 rounded-full shadow-md">

                    <div class="absolute bottom-0 right-0 px-2 py-1 text-xs font-bold text-white rounded-lg shadow"
                        :class="{
                            'bg-green-500': attendanceStatus === 'Hadir',
                            'bg-red-500': attendanceStatus === 'Telat',
                            'bg-blue-500': attendanceStatus === 'Pulang',
                            'bg-gray-500': attendanceStatus === 'Selesai'
                        }"
                        x-text="attendanceStatus">
                    </div>
                </div>

                <h3 class="text-xl font-bold text-gray-900" x-text="studentName"></h3>
                <p class="text-sm font-medium text-blue-600" x-text="message"></p>

                <!-- Info Barcode -->
                <div class="w-full p-3 mt-3 border rounded-lg bg-gray-50">
                    <div id="barcode-display" class="flex flex-col items-center mb-1">
                        <!-- Merender Barcode HTML dari Server -->
                        <div x-html="barcodeHtml" class="p-2 mb-2 bg-white rounded shadow-sm"></div>
                        <p class="font-mono text-xs text-gray-400" x-text="'ID: ' + studentBarcode"></p>
                    </div>
                </div>
            </div>

            <!-- Tampilan Error -->
            <div x-show="status === 'error'" x-transition class="flex flex-col items-center">
                <div class="flex items-center justify-center w-20 h-20 mx-auto mb-4 text-red-600 bg-red-100 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-red-600">Gagal!</h3>
                <p class="text-sm font-medium text-gray-600" x-text="message"></p>
            </div>

            <!-- Input Hidden/Focus -->
            <form @submit.prevent="submitScan">
                <input type="text" x-ref="rfidInput" x-model="rfid_uid" @input="handleInput()"
                    class="block w-full p-3 mt-4 font-mono text-lg text-center border-2 border-blue-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300"
                    placeholder="Scanning..." autofocus @blur="$refs.rfidInput.focus()">
            </form>

            <p class="mt-6 text-xs italic text-gray-400">Sistem akan otomatis memproses setelah scan</p>
        </div>
    </div>

    <script>
        function scanHandler() {
            return {
                rfid_uid: '',
                status: 'idle',
                message: '',
                studentName: '',
                studentPhoto: '',
                studentBarcode: '',
                barcodeHtml: '', // Properti baru untuk menyimpan HTML barcode
                attendanceStatus: '',
                timeout: null,
                audioSuccess: new Audio('https://assets.mixkit.co/active_storage/sfx/2216/2216-preview.mp3'),
                audioError: new Audio('https://assets.mixkit.co/active_storage/sfx/2190/2190-preview.mp3'),

                handleInput() {
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        if (this.rfid_uid.length >= 3) {
                            this.submitScan();
                        }
                    }, 500);
                },

                submitScan() {
                    if (!this.rfid_uid) return;

                    fetch("/scan/store", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ rfid_uid: this.rfid_uid })
                    })
                    .then(response => {
                        if (!response.ok) return response.json().then(err => { throw err; });
                        return response.json();
                    })
                    .then(data => {
                        this.status = data.status;
                        this.message = data.message;
                        this.studentName = data.student_name;
                        this.studentPhoto = data.student_photo;
                        this.studentBarcode = data.barcode;
                        this.barcodeHtml = data.barcode_html; // Ambil HTML dari respons
                        this.attendanceStatus = data.attendance_status;

                        this.audioSuccess.play();
                        this.rfid_uid = '';

                        setTimeout(() => {
                            if(this.status !== 'error') this.status = 'idle';
                        }, 5000);
                    })
                    .catch(error => {
                        this.status = 'error';
                        this.message = error.message || 'Siswa tidak ditemukan!';
                        this.rfid_uid = '';
                        this.audioError.play();

                        setTimeout(() => {
                            this.status = 'idle';
                        }, 3000);
                    });
                }
            }
        }
    </script>
@endsection
