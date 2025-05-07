<!-- Toast Notification Manager -->
<div 
    x-data="{
        toasts: [],
        add(message) {
            const id = Date.now();
            this.toasts.push({ id, message, progress: 100 });
            let start = Date.now();
            const duration = 10000;
            const toast = this.toasts.find(t => t.id === id);
            const step = () => {
                let elapsed = Date.now() - start;
                toast.progress = 100 - (elapsed / duration) * 100;
                if (elapsed < duration) {
                    requestAnimationFrame(step);
                } else {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }
            };
            requestAnimationFrame(step);
        }
    }"
    x-init="
        @if(session('success'))
            add(@js(session('success')));
        @endif
    "
    class="fixed top-4 right-4 z-50 space-y-4"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition
            class="bg-green-600 text-white text-sm font-medium px-5 py-3 rounded-lg shadow-lg flex flex-col gap-2 relative overflow-hidden"
        >
            <div class="flex items-start justify-between">
                <span x-text="toast.message"></span>
                <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="text-white hover:text-gray-300 ml-2 text-lg leading-none">
                    &times;
                </button>
            </div>
            <div class="absolute bottom-0 left-0 h-1 bg-green-200 transition-all duration-[100ms] ease-linear"
                :style="'width: ' + toast.progress + '%'">
            </div>
        </div>
    </template>
</div>

<div 
    x-data="{
        toasts: [],
        add(message) {
            const id = Date.now();
            this.toasts.push({ id, message, progress: 100 });
            let start = Date.now();
            const duration = 10000;
            const toast = this.toasts.find(t => t.id === id);
            const step = () => {
                let elapsed = Date.now() - start;
                toast.progress = 100 - (elapsed / duration) * 100;
                if (elapsed < duration) {
                    requestAnimationFrame(step);
                } else {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }
            };
            requestAnimationFrame(step);
        }
    }"
    x-init="
        @if(session('error'))
            add(@js(session('error')));
        @endif
    "
    class="fixed top-4 right-4 z-50 space-y-4"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition
            class="bg-red-600 text-white text-sm font-medium px-5 py-3 rounded-lg shadow-lg flex flex-col gap-2 relative overflow-hidden"
        >
            <div class="flex items-start justify-between">
                <span x-text="toast.message"></span>
                <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="text-white hover:text-gray-300 ml-2 text-lg leading-none">
                    &times;
                </button>
            </div>
            <div class="absolute bottom-0 left-0 h-1 bg-red-300 transition-all duration-[100ms] ease-linear"
                :style="'width: ' + toast.progress + '%'">
            </div>
        </div>
    </template>
</div>
