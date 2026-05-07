@extends('layouts.app')

@section('title', 'Scan Absensi')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[70vh]" x-data="scanHandler()">
        <div class="relative w-full max-w-md p-10 overflow-hidden text-center bg-white border border-gray-100 shadow-2xl rounded-[2.5rem]">

            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 to-[#773DCE]"></div>

            <div x-show="status === 'idle'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100">
                <div class="flex items-center justify-center w-24 h-24 mx-auto mb-6 bg-purple-50 rounded-3xl text-[#773DCE] animate-pulse">
                    <i class="text-5xl fa-solid fa-qrcode"></i>
                </div>
                <h2 class="text-2xl font-black text-gray-800">Scan Barcode</h2>
                <p class="mt-2 text-sm font-medium tracking-widest text-gray-400 uppercase">Silahkan scan kartu siswa</p>
            </div>

            <div x-show="status !== 'idle' && status !== 'error'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translateY(20px)"
                x-transition:enter-end="opacity-100 transform translateY(0)" class="flex flex-col items-center">

                <div class="relative mb-6">
                    <div class="absolute bg-purple-100 rounded-full opacity-50 -inset-2 blur-lg animate-pulse"></div>
                    <img :src="studentPhoto"
                        class="relative object-cover border-4 border-white rounded-full shadow-xl w-36 h-36 ring-4 ring-purple-50">

                    <div class="absolute -bottom-1 -right-1 px-4 py-1.5 text-[10px] font-black text-white rounded-full shadow-lg uppercase tracking-wider"
                        :class="{
                            'bg-green-500 shadow-green-100': attendanceStatus === 'Hadir',
                            'bg-amber-500 shadow-amber-100': attendanceStatus === 'Telat',
                            'bg-blue-500 shadow-blue-100': attendanceStatus === 'Pulang',
                            'bg-gray-500 shadow-gray-100': attendanceStatus === 'Selesai'
                        }"
                        x-text="attendanceStatus">
                    </div>
                </div>

                <h3 class="text-2xl font-black leading-tight text-gray-800" x-text="studentName"></h3>
                <div class="px-4 py-1 mt-2 rounded-full bg-purple-50">
                    <p class="text-xs font-bold text-[#773DCE]" x-text="message"></p>
                </div>

                <div class="w-full p-4 mt-6 border border-gray-50 rounded-2xl bg-gray-50/50">
                    <div id="barcode-display" class="flex flex-col items-center">
                        <div x-html="barcodeHtml" class="p-3 mb-2 bg-white shadow-sm rounded-xl"></div>
                        <p class="font-mono text-[10px] font-bold text-gray-400 tracking-[0.2em]" x-text="studentBarcode"></p>
                    </div>
                </div>
            </div>

            <div x-show="status === 'error'" x-transition class="flex flex-col items-center">
                <div class="flex items-center justify-center w-24 h-24 mx-auto mb-6 text-red-500 bg-red-50 rounded-3xl">
                    <i class="text-5xl fa-solid fa-circle-xmark"></i>
                </div>
                <h3 class="text-2xl font-black text-red-600">Gagal!</h3>
                <p class="mt-2 text-sm font-bold text-gray-500" x-text="message"></p>
            </div>

            <form @submit.prevent="submitScan" class="mt-8">
                <div class="relative">
                    <input type="text" x-ref="rfidInput" x-model="rfid_uid" @input="handleInput()"
                        class="block w-full p-4 font-mono text-lg text-center text-[#773DCE] bg-purple-50/30 border-2 border-purple-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] transition-all placeholder-purple-200"
                        placeholder="Menunggu scan..." autofocus @blur="$refs.rfidInput.focus()">
                    <div class="absolute -translate-y-1/2 right-4 top-1/2">
                        <i class="fa-solid fa-sm fa-circle text-[#773DCE] animate-ping"></i>
                    </div>
                </div>
            </form>

            <p class="mt-6 text-[10px] font-bold uppercase tracking-widest text-gray-300">
                <i class="mr-1 fa-solid fa-robot"></i> Sistem Otomatis SDIT Ummatan Wahidah
            </p>
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
                barcodeHtml: '',
                attendanceStatus: '',
                timeout: null,
                // Menggunakan audio yang lebih clean
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
                        this.barcodeHtml = data.barcode_html;
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
