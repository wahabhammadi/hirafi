<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('تفاصيل العرض')); ?> #<?php echo e($offre->id); ?>

            </h2>
            <a href="<?php echo e(route('client.offres.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <?php echo e(__('العودة إلى قائمة العروض')); ?>

            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- رسائل النجاح -->
            <?php if(session('status')): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p><?php echo e(session('status')); ?></p>
                </div>
            <?php endif; ?>
            
            <!-- معلومات العرض -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php echo e(__('معلومات العرض')); ?></h3>
                        
                        <!-- حالة العرض -->
                        <div>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                <?php if($offre->status == 'pending'): ?> bg-yellow-100 text-yellow-800 <?php endif; ?>
                                <?php if($offre->status == 'accepted'): ?> bg-green-100 text-green-800 <?php endif; ?>
                                <?php if($offre->status == 'rejected'): ?> bg-red-100 text-red-800 <?php endif; ?>
                                <?php if($offre->status == 'completed'): ?> bg-purple-100 text-purple-800 <?php endif; ?>
                            ">
                                <?php if($offre->status == 'pending'): ?>
                                    <?php echo e(__('قيد الانتظار')); ?>

                                <?php elseif($offre->status == 'accepted'): ?>
                                    <?php echo e(__('مقبول')); ?>

                                <?php elseif($offre->status == 'rejected'): ?>
                                    <?php echo e(__('مرفوض')); ?>

                                <?php elseif($offre->status == 'completed'): ?>
                                    <?php echo e(__('مكتمل')); ?>

                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4"><?php echo e(__('المشروع')); ?></h4>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('عنوان المشروع')); ?></span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">
                                        <a href="<?php echo e(route('commandes.show', $offre->commande_id)); ?>" class="text-blue-600 hover:underline dark:text-blue-400">
                                            <?php echo e($offre->commande->titre); ?>

                                        </a>
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('ميزانية المشروع')); ?></span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($offre->commande->budget); ?> دج</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4"><?php echo e(__('معلومات الحرفي')); ?></h4>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('اسم الحرفي')); ?></span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($offre->user->name); ?></span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('أرقام الهاتف')); ?></span>
                                    <div class="mt-1 space-y-1">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                            </svg>
                                            <a href="tel:<?php echo e($offre->user->phone); ?>" class="text-blue-600 hover:underline dark:text-blue-400">
                                                <?php echo e($offre->user->phone ?: __('غير متوفر')); ?>

                                            </a>
                                            <span class="mr-2 text-xs text-gray-500 dark:text-gray-400">(<?php echo e(__('رئيسي')); ?>)</span>
                                        </div>
                                        
                                        <?php if(isset($offre->user->craftsman) && $offre->user->craftsman->phone_secondary): ?>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                            </svg>
                                            <a href="tel:<?php echo e($offre->user->craftsman->phone_secondary); ?>" class="text-blue-600 hover:underline dark:text-blue-400">
                                                <?php echo e($offre->user->craftsman->phone_secondary); ?>

                                            </a>
                                            <span class="mr-2 text-xs text-gray-500 dark:text-gray-400">(<?php echo e(__('ثانوي')); ?>)</span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if(isset($offre->user->craftsman)): ?>
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('التخصص')); ?></span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($offre->user->craftsman->craft ?? 'غير محدد'); ?></span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('التقييم العام')); ?></span>
                                    <div class="mt-1 flex items-center">
                                        <?php if(isset($offre->user->craftsman->rating)): ?>
                                            <?php if (isset($component)) { $__componentOriginalfa87e49ca3cdf62358bbc468aaf3394b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa87e49ca3cdf62358bbc468aaf3394b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.star-rating','data' => ['rating' => $offre->user->craftsman->rating,'size' => 'md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('star-rating'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['rating' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($offre->user->craftsman->rating),'size' => 'md']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfa87e49ca3cdf62358bbc468aaf3394b)): ?>
<?php $attributes = $__attributesOriginalfa87e49ca3cdf62358bbc468aaf3394b; ?>
<?php unset($__attributesOriginalfa87e49ca3cdf62358bbc468aaf3394b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfa87e49ca3cdf62358bbc468aaf3394b)): ?>
<?php $component = $__componentOriginalfa87e49ca3cdf62358bbc468aaf3394b; ?>
<?php unset($__componentOriginalfa87e49ca3cdf62358bbc468aaf3394b); ?>
<?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-sm text-gray-600 dark:text-gray-400"><?php echo e(__('لا يوجد تقييم بعد')); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4"><?php echo e(__('تفاصيل العرض')); ?></h4>
                                    
                                    <div class="mb-3">
                                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('السعر المقترح')); ?></span>
                                        <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($offre->price); ?> دج</span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('تاريخ التسليم المتوقع')); ?></span>
                                        <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($offre->delivery_date->format('Y-m-d')); ?></span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('تاريخ تقديم العرض')); ?></span>
                                        <span class="block mt-1 text-gray-800 dark:text-gray-200"><?php echo e($offre->created_at->format('Y-m-d H:i')); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4"><?php echo e(__('وصف طريقة العمل')); ?></h4>
                                    <div class="text-gray-800 dark:text-gray-200 whitespace-pre-line"><?php echo e($offre->description); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(isset($offre->media) && count($offre->media) > 0): ?>
                    <div class="mt-6">
                        <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4"><?php echo e(__('الوسائط المرفقة')); ?></h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <?php $__currentLoopData = $offre->media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="rounded-lg overflow-hidden shadow">
                                    <?php if(isset($media['type']) && str_contains($media['type'], 'image')): ?>
                                        <a href="<?php echo e(isset($media['path']) ? asset('storage/' . $media['path']) : $media['url']); ?>" target="_blank">
                                            <img src="<?php echo e(isset($media['path']) ? asset('storage/' . $media['path']) : $media['url']); ?>" 
                                                alt="<?php echo e($media['name'] ?? 'صورة العرض'); ?>" 
                                                class="h-40 w-full object-cover">
                                        </a>
                                    <?php elseif(isset($media['type']) && str_contains($media['type'], 'video')): ?>
                                        <video class="h-40 w-full object-cover" controls>
                                            <source src="<?php echo e(isset($media['path']) ? asset('storage/' . $media['path']) : $media['url']); ?>" type="<?php echo e($media['type']); ?>">
                                            <?php echo e(__('متصفحك لا يدعم عرض الفيديو')); ?>

                                        </video>
                                    <?php elseif(isset($media['type']) && $media['type'] == 'url'): ?>
                                        <div class="h-40 p-4 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                                            <a href="<?php echo e($media['url']); ?>" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline break-all">
                                                <?php echo e($media['url']); ?>

                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- عرض ملف أعمال الحرفي -->
                    <?php if(isset($offre->user) && $offre->user->role === 'craftsman'): ?>
                    <div class="mt-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300"><?php echo e(__('ملف أعمال الحرفي')); ?></h4>
                                <a href="<?php echo e(route('craftsman.profile', $offre->user->id)); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                                    <?php echo e(__('عرض الملف الكامل')); ?> &larr;
                                </a>
                            </div>
                            
                            <?php
                                $featuredWorks = $offre->user->works()
                                    ->where('is_featured', true)
                                    ->latest()
                                    ->take(3)
                                    ->get();
                                
                                if ($featuredWorks->count() === 0) {
                                    $featuredWorks = $offre->user->works()
                                        ->latest()
                                        ->take(3)
                                        ->get();
                                }
                            ?>
                            
                            <?php if($featuredWorks->count() > 0): ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                    <?php $__currentLoopData = $featuredWorks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $work): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                                            <?php if(isset($work->media) && is_array($work->media) && count($work->media) > 0): ?>
                                                <?php
                                                    $firstMedia = $work->media[0];
                                                ?>
                                                
                                                <?php if(isset($firstMedia['type']) && str_contains($firstMedia['type'], 'image')): ?>
                                                    <div class="h-40 overflow-hidden">
                                                        <img src="<?php echo e(isset($firstMedia['path']) ? asset('storage/' . $firstMedia['path']) : $firstMedia['url']); ?>" 
                                                            alt="<?php echo e($work->title); ?>" 
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                <?php elseif(isset($firstMedia['type']) && str_contains($firstMedia['type'], 'video')): ?>
                                                    <div class="h-40 overflow-hidden">
                                                        <video class="w-full h-full object-cover" controls>
                                                            <source src="<?php echo e(isset($firstMedia['path']) ? asset('storage/' . $firstMedia['path']) : $firstMedia['url']); ?>" type="<?php echo e($firstMedia['type']); ?>">
                                                        </video>
                                                    </div>
                                                <?php elseif(isset($firstMedia['type']) && $firstMedia['type'] === 'url'): ?>
                                                    <div class="h-40 bg-gray-100 dark:bg-gray-700 flex items-center justify-center p-4">
                                                        <a href="<?php echo e($firstMedia['url']); ?>" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline text-center break-all">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                            </svg>
                                                            <?php echo e(__('رابط خارجي')); ?>

                                                        </a>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="h-40 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div class="h-40 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="p-3">
                                                <h5 class="font-medium text-gray-800 dark:text-gray-200 mb-1 truncate"><?php echo e($work->title); ?></h5>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2"><?php echo e(Str::limit($work->description, 60)); ?></p>
                                                <p class="text-xs text-gray-500 mt-1"><?php echo e($work->completion_date->format('Y-m-d')); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <p><?php echo e(__('لا توجد أعمال في ملف الحرفي حتى الآن')); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- الإجراءات - قبول/رفض العرض أو تقييم الحرفي -->
                    <div class="mt-10 border-t pt-6 border-gray-200 dark:border-gray-700">
                        <?php if($offre->status == 'pending'): ?>
                        <div class="flex justify-end items-center space-x-4 rtl:space-x-reverse">
                            <form action="<?php echo e(route('client.offres.destroy', $offre)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('هل أنت متأكد من حذف هذا العرض؟')); ?>');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <?php echo e(__('حذف العرض')); ?>

                                </button>
                            </form>
                            
                            <form action="<?php echo e(route('client.offres.update-status', $offre)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('هل أنت متأكد من رفض هذا العرض؟')); ?>');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    <?php echo e(__('رفض العرض')); ?>

                                </button>
                            </form>
                            
                            <form action="<?php echo e(route('client.offres.update-status', $offre)); ?>" method="POST" class="mr-4" onsubmit="return confirm('<?php echo e(__('هل أنت متأكد من قبول هذا العرض؟ سيتم رفض جميع العروض الأخرى لهذا المشروع.')); ?>');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <?php echo e(__('قبول العرض')); ?>

                                </button>
                            </form>
                        </div>
                        
                        <?php elseif($offre->status == 'accepted'): ?>
                        <div>
                            <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="mr-3">
                                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                            <?php echo e(__('تم قبول هذا العرض وهو قيد التنفيذ')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <form action="<?php echo e(route('client.offres.update-status', $offre)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('هل تم إكمال العمل بالفعل؟')); ?>');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="status" value="completed">
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <?php echo e(__('تأكيد إكمال العمل')); ?>

                                    </button>
                                </div>
                            </form>
                        </div>

                        <?php elseif($offre->status == 'completed'): ?>
                            <div class="flex justify-between items-start">
                                <div class="flex-grow">
                                    <?php if(!isset($offre->rating)): ?>
                                    <div x-data="{ rating: 5, hoverRating: 0 }">
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4"><?php echo e(__('تقييم الحرفي')); ?></h4>
                                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg mb-6">
                                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                                <?php echo e(__('الرجاء تقييم الحرفي بعد إكمال العمل. سيساعد تقييمك الآخرين في العثور على حرفيين جيدين.')); ?>

                                            </p>
                                        </div>
                                        
                                        <form action="<?php echo e(route('client.offres.rate', $offre)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('التقييم')); ?></label>
                                                <div class="flex items-center space-x-1 rtl:space-x-reverse">
                                                    <input type="hidden" name="rating" x-model="rating">
                                                    <div class="flex flex-row-reverse">
                                                        <template x-for="i in 5">
                                                            <button type="button" 
                                                                @click="rating = i" 
                                                                @mouseover="hoverRating = i" 
                                                                @mouseleave="hoverRating = 0"
                                                                class="focus:outline-none">
                                                                <svg class="w-8 h-8 cursor-pointer" 
                                                                    :class="{'text-yellow-400': (i <= hoverRating) || (hoverRating === 0 && i <= rating), 'text-gray-300': (i > hoverRating) && (hoverRating > 0 || i > rating)}"
                                                                    fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                            </button>
                                                        </template>
                                                    </div>
                                                    <p class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        <span x-text="rating"></span>/5
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label for="review" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"><?php echo e(__('تعليق (اختياري)')); ?></label>
                                                <textarea id="review" name="review" rows="4" class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" placeholder="<?php echo e(__('شارك تجربتك مع هذا الحرفي...')); ?>"></textarea>
                                            </div>
                                            
                                            <div class="flex justify-end">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                                    <?php echo e(__('إرسال التقييم')); ?>

                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <?php else: ?>
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4"><?php echo e(__('تقييمك للحرفي')); ?></h4>
                                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                            <div class="mb-2">
                                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"><?php echo e(__('التقييم')); ?></span>
                                                <?php if (isset($component)) { $__componentOriginalfa87e49ca3cdf62358bbc468aaf3394b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa87e49ca3cdf62358bbc468aaf3394b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.star-rating','data' => ['rating' => $offre->rating,'size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('star-rating'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['rating' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($offre->rating),'size' => 'lg']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfa87e49ca3cdf62358bbc468aaf3394b)): ?>
<?php $attributes = $__attributesOriginalfa87e49ca3cdf62358bbc468aaf3394b; ?>
<?php unset($__attributesOriginalfa87e49ca3cdf62358bbc468aaf3394b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfa87e49ca3cdf62358bbc468aaf3394b)): ?>
<?php $component = $__componentOriginalfa87e49ca3cdf62358bbc468aaf3394b; ?>
<?php unset($__componentOriginalfa87e49ca3cdf62358bbc468aaf3394b); ?>
<?php endif; ?>
                                            </div>
                                            
                                            <?php if($offre->review): ?>
                                            <div>
                                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"><?php echo e(__('تعليقك')); ?></span>
                                                <p class="text-gray-800 dark:text-gray-200"><?php echo e($offre->review); ?></p>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-4">
                                    <form action="<?php echo e(route('client.offres.destroy', $offre)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('هل أنت متأكد من حذف هذا العرض؟')); ?>');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            <?php echo e(__('حذف العرض')); ?>

                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php elseif($offre->status == 'rejected'): ?>
                        <div class="flex justify-between items-center">
                            <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="mr-3">
                                        <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                            <?php echo e(__('تم رفض هذا العرض')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <form action="<?php echo e(route('client.offres.destroy', $offre)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('هل أنت متأكد من حذف هذا العرض؟')); ?>');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <?php echo e(__('حذف العرض')); ?>

                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?> <?php /**PATH C:\laragon\www\hirafi\resources\views/client/offres/show.blade.php ENDPATH**/ ?>