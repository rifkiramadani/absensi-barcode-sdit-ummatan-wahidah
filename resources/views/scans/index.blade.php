@extends('layouts.app')

@section('title', 'Scan Absensi')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[70vh]" x-data="scanHandler()">
        <div class="relative w-full max-w-md p-10 overflow-hidden text-center bg-white border border-gray-100 shadow-2xl rounded-[2.5rem]">

            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 to-[#773DCE]"></div>

            {{-- STATE: IDLE --}}
            <div x-show="status === 'idle'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100">
                <div class="flex items-center justify-center w-24 h-24 mx-auto mb-6 bg-purple-50 rounded-3xl text-[#773DCE] animate-pulse">
                    <i class="text-5xl fa-solid fa-qrcode"></i>
                </div>
                <h2 class="text-2xl font-black text-gray-800">Scan Barcode</h2>
                <p class="mt-2 text-sm font-medium tracking-widest text-gray-400 uppercase">Silahkan scan kartu siswa</p>
            </div>

            {{-- STATE: SUCCESS / INFO --}}
            <div x-show="status === 'success' || status === 'info'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translateY(20px)"
                x-transition:enter-end="opacity-100 transform translateY(0)"
                class="flex flex-col items-center">

                <div class="relative mb-6">
                    <div class="absolute bg-purple-100 rounded-full opacity-50 -inset-2 blur-lg animate-pulse"></div>
                    <img :src="studentPhoto"
                        class="relative object-cover border-4 border-white rounded-full shadow-xl w-36 h-36 ring-4 ring-purple-50">

                    <div class="absolute -bottom-1 -right-1 px-4 py-1.5 text-[10px] font-black text-white rounded-full shadow-lg uppercase tracking-wider"
                        :class="{
                            'bg-green-500 shadow-green-100': attendanceStatus === 'Hadir',
                            'bg-amber-500 shadow-amber-100': attendanceStatus === 'Telat',
                            'bg-blue-500 shadow-blue-100':  attendanceStatus === 'Pulang',
                            'bg-gray-500 shadow-gray-100':  attendanceStatus === 'Selesai',
                        }"
                        x-text="attendanceStatus">
                    </div>
                </div>

                <h3 class="text-2xl font-black leading-tight text-gray-800" x-text="studentName"></h3>
                <div class="px-4 py-1 mt-2 rounded-full bg-purple-50">
                    <p class="text-xs font-bold text-[#773DCE]" x-text="message"></p>
                </div>

                <div class="w-full p-4 mt-6 border border-gray-50 rounded-2xl bg-gray-50/50">
                    <div class="flex flex-col items-center">
                        <div x-html="barcodeHtml" class="p-3 mb-2 bg-white shadow-sm rounded-xl"></div>
                        <p class="font-mono text-[10px] font-bold text-gray-400 tracking-[0.2em]" x-text="studentBarcode"></p>
                    </div>
                </div>
            </div>

            {{-- STATE: KETERANGAN (Izin / Sakit / Alpa) --}}
            <div x-show="status === 'keterangan'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                class="flex flex-col items-center">

                <div class="relative mb-6">
                    <div class="absolute rounded-full opacity-40 -inset-2 blur-lg animate-pulse"
                        :class="{
                            'bg-blue-200':   keterangan === 'Izin',
                            'bg-orange-200': keterangan === 'Sakit',
                            'bg-red-200':    keterangan === 'Alpa',
                        }"></div>
                    <img :src="studentPhoto"
                        class="relative object-cover border-4 border-white rounded-full shadow-xl w-36 h-36"
                        :class="{
                            'ring-4 ring-blue-200':   keterangan === 'Izin',
                            'ring-4 ring-orange-200': keterangan === 'Sakit',
                            'ring-4 ring-red-200':    keterangan === 'Alpa',
                        }">
                    <div class="absolute -bottom-1 -right-1 px-4 py-1.5 text-[10px] font-black text-white rounded-full shadow-lg uppercase tracking-wider"
                        :class="{
                            'bg-blue-500 shadow-blue-200':     keterangan === 'Izin',
                            'bg-orange-500 shadow-orange-200': keterangan === 'Sakit',
                            'bg-red-500 shadow-red-200':       keterangan === 'Alpa',
                        }"
                        x-text="attendanceStatus">
                    </div>
                </div>

                <h3 class="text-2xl font-black leading-tight text-gray-800" x-text="studentName"></h3>

                <div class="flex items-center justify-center w-16 h-16 mx-auto mt-4 mb-3 rounded-2xl"
                    :class="{
                        'bg-blue-50':   keterangan === 'Izin',
                        'bg-orange-50': keterangan === 'Sakit',
                        'bg-red-50':    keterangan === 'Alpa',
                    }">
                    <i class="text-3xl fa-solid"
                        :class="{
                            'fa-file-circle-check text-blue-500': keterangan === 'Izin',
                            'fa-kit-medical text-orange-500':     keterangan === 'Sakit',
                            'fa-circle-xmark text-red-500':       keterangan === 'Alpa',
                        }"></i>
                </div>

                <div class="px-5 py-2 rounded-2xl"
                    :class="{
                        'bg-blue-50':   keterangan === 'Izin',
                        'bg-orange-50': keterangan === 'Sakit',
                        'bg-red-50':    keterangan === 'Alpa',
                    }">
                    <p class="text-sm font-bold"
                        :class="{
                            'text-blue-600':   keterangan === 'Izin',
                            'text-orange-600': keterangan === 'Sakit',
                            'text-red-600':    keterangan === 'Alpa',
                        }"
                        x-text="message"></p>
                </div>
            </div>

            {{-- STATE: ERROR --}}
            <div x-show="status === 'error'" x-transition class="flex flex-col items-center">
                <div class="flex items-center justify-center w-24 h-24 mx-auto mb-6 text-red-500 bg-red-50 rounded-3xl">
                    <i class="text-5xl fa-solid fa-circle-xmark"></i>
                </div>
                <h3 class="text-2xl font-black text-red-600">Gagal!</h3>
                <p class="mt-2 text-sm font-bold text-gray-500" x-text="message"></p>
            </div>

            {{-- Input Scan --}}
            {{--
                PENTING: Hapus @blur karena justru bermasalah dengan scanner fisik.
                Focus diurus sepenuhnya oleh keepFocus() via interval + event listener.
            --}}
            {{-- Input Scan --}}
            <form @submit.prevent="submitScan" class="mt-8">
                <div class="relative">
                    <input type="text"
                        id="rfidInput"
                        x-ref="rfidInput"
                        x-model="rfid_uid"
                        @keydown.enter.prevent="submitScan()"
                        @keydown.tab.prevent
                        @input="handleInput()"
                        class="block w-full p-4 font-mono text-lg text-center text-[#773DCE] bg-purple-50/30 border-2 border-purple-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] transition-all placeholder-purple-200"
                        placeholder="Menunggu scan..."
                        autocomplete="off"
                        spellcheck="false"
                        autofocus>
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
                keterangan: '',
                timeout: null,
                lastScanned: '',
                lastScannedTime: 0,
                focusInterval: null,
                audioSuccess: new Audio("{{ asset('assets/sounds/beep.mp3') }}"),
                audioError:   new Audio("{{ asset('assets/sounds/beep.mp3') }}"),

                init() {
                    this.$nextTick(() => this.refocus());

                    // Perkecil interval ke 50ms agar lebih responsif
                    this.focusInterval = setInterval(() => this.refocus(), 50);

                    // Klik di mana saja → refocus
                    document.addEventListener('click', (e) => {
                        if (!['INPUT', 'BUTTON', 'A', 'SELECT', 'TEXTAREA'].includes(e.target.tagName)) {
                            this.refocus();
                        }
                    });

                    // Saat halaman dapat fokus kembali
                    window.addEventListener('focus', () => {
                        setTimeout(() => this.refocus(), 50);
                    });

                    // Intercept keydown di level document — tangkap Enter SEBELUM browser bereaksi
                    document.addEventListener('keydown', (e) => {
                        const el = this.$refs.rfidInput;
                        if (e.key === 'Enter' || e.key === 'Return') {
                            e.preventDefault();
                            e.stopPropagation();
                            if (this.rfid_uid.length >= 1) {
                                this.submitScan();
                            }
                        }
                        // Jika ada input karakter dan fokus bukan di input, pindahkan fokus dulu
                        if (e.key.length === 1 && document.activeElement !== el) {
                            e.preventDefault();
                            this.refocus();
                            // Tambahkan karakter ke rfid_uid secara manual
                            this.rfid_uid += e.key;
                            this.handleInput();
                        }
                    }, true); // useCapture: true agar jalan sebelum event lain
                },

                refocus() {
                    const el = this.$refs.rfidInput;
                    if (el && document.activeElement !== el) {
                        el.focus();
                    }
                },

                handleInput() {
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        if (this.rfid_uid.length >= 1) {
                            this.submitScan();
                        }
                    }, 130);
                },

                submitScan() {
                    if (!this.rfid_uid) return;

                    clearTimeout(this.timeout); // Pastikan timeout tidak double-fire

                    const now = Date.now();

                    if (this.rfid_uid === this.lastScanned && (now - this.lastScannedTime) < 2000) {
                        this.rfid_uid = '';
                        this.$nextTick(() => this.refocus());
                        return;
                    }

                    this.lastScanned     = this.rfid_uid;
                    this.lastScannedTime = now;

                    const scannedValue = this.rfid_uid;
                    this.rfid_uid = '';
                    this.$nextTick(() => this.refocus()); // Refocus SEGERA setelah clear

                    fetch("/scan/store", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ rfid_uid: scannedValue })
                    })
                    .then(response => {
                        if (!response.ok) return response.json().then(err => { throw err; });
                        return response.json();
                    })
                    .then(data => {
                        this.status           = data.status;
                        this.message          = data.message;
                        this.studentName      = data.student_name;
                        this.studentPhoto     = data.student_photo;
                        this.studentBarcode   = data.barcode      ?? '';
                        this.barcodeHtml      = data.barcode_html ?? '';
                        this.attendanceStatus = data.attendance_status;
                        this.keterangan       = data.keterangan   ?? '';

                        this.$nextTick(() => this.refocus());

                        const delay = data.status === 'keterangan' ? 4000 : 3500;
                        if (data.status === 'keterangan') {
                            this.audioError.play().catch(() => {});
                        } else {
                            this.audioSuccess.play().catch(() => {});
                        }

                        setTimeout(() => {
                            this.status     = 'idle';
                            this.keterangan = '';
                            this.refocus();
                        }, delay);
                    })
                    .catch(error => {
                        this.status     = 'error';
                        this.message    = error.message || 'Kartu tidak terdaftar!';
                        this.keterangan = '';
                        this.audioError.play().catch(() => {});

                        this.$nextTick(() => this.refocus());

                        setTimeout(() => {
                            this.status = 'idle';
                            this.refocus();
                        }, 2000);
                    });
                }
            }
        }
    </script>
@endsection
