<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

/**
 * DemoSeeder
 *
 * Seeds 12 realistic-looking demo courses so you can view the UI
 * without making any real API calls.
 *
 * Run: php artisan db:seed --class=DemoSeeder
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'playlist_id'   => 'PLfqMhTWNBTe3H6c9OGXb5_6wcc1Mcd7gY',
                'title'         => 'أساسيات التسويق الرقمي للمبتدئين - دورة كاملة',
                'description'   => 'تعلم أساسيات التسويق الرقمي من الصفر.',
                'thumbnail_url' => 'https://picsum.photos/seed/mkt1/320/180',
                'channel_name'  => 'تعلم التسويق العربي',
                'category'      => 'التسويق',
                'video_count'   => 42,
                'playlist_url'  => 'https://www.youtube.com/playlist?list=PLfqMhTWNBTe3H6c9OGXb5_6wcc1Mcd7gY',
            ],
            [
                'playlist_id'   => 'PLknwEmKsW8OtK_n48UOuYGxJPbSFrICxm',
                'title'         => 'دورة التسويق عبر وسائل التواصل الاجتماعي',
                'description'   => 'كيفية استخدام السوشيال ميديا لتنمية عملك.',
                'thumbnail_url' => 'https://picsum.photos/seed/mkt2/320/180',
                'channel_name'  => 'أكاديمية التسويق',
                'category'      => 'التسويق',
                'video_count'   => 38,
                'playlist_url'  => 'https://www.youtube.com/playlist?list=PLknwEmKsW8OtK_n48UOuYGxJPbSFrICxm',
            ],
            [
                'playlist_id'   => 'PLdo4fOcmZ0oV0vuygnQ-oXADKy_HvFi1j',
                'title'         => 'تعلم البرمجة بلغة بايثون - دورة كاملة',
                'description'   => 'من المبتدئ إلى المتقدم في لغة Python.',
                'thumbnail_url' => 'https://picsum.photos/seed/py1/320/180',
                'channel_name'  => 'البرمجة العربية',
                'category'      => 'البرمجة',
                'video_count'   => 95,
                'playlist_url'  => 'https://www.youtube.com/playlist?list=PLdo4fOcmZ0oV0vuygnQ-oXADKy_HvFi1j',
            ],
            [
                'playlist_id'   => 'PLDoPjvoNmBAw_t_XWUFbBX-c9MafPk9ji',
                'title'         => 'دورة JavaScript من المبتدئ إلى المتقدم',
                'description'   => 'تعلم JavaScript الحديثة بشكل شامل.',
                'thumbnail_url' => 'https://picsum.photos/seed/js1/320/180',
                'channel_name'  => 'أبو عزيز',
                'category'      => 'البرمجة',
                'video_count'   => 120,
                'playlist_url'  => 'https://www.youtube.com/playlist?list=PLDoPjvoNmBAw_t_XWUFbBX-c9MafPk9ji',
            ],
            [
                'playlist_id'   => 'PLam8Wk3e0qlzrFgGbK1bvxOIBkrLNnhz3',
                'title'         => 'تعلم التصميم الجرافيكي باستخدام فوتوشوب',
                'description'   => 'دورة شاملة في Adobe Photoshop للمبتدئين.',
                'thumbnail_url' => 'https://picsum.photos/seed/ps1/320/180',
                'channel_name'  => 'أكاديمية الإبداع',
                'category'      => 'الجرافيكس',
                'video_count'   => 55,
                'playlist_url'  => 'https://www.youtube.com/playlist?list=PLam8Wk3e0qlzrFgGbK1bvxOIBkrLNnhz3',
            ],
            [
                'playlist_id'   => 'PLCvlyb1ChyzHj_v2vXBkHZZTQDPBPBpBl',
                'title'         => 'تصميم الشعارات الاحترافية - Adobe Illustrator',
                'description'   => 'تعلم تصميم الشعارات والهويات البصرية.',
                'thumbnail_url' => 'https://picsum.photos/seed/ai1/320/180',
                'channel_name'  => 'قبس في التصميم',
                'category'      => 'الجرافيكس',
                'video_count'   => 30,
                'playlist_url'  => 'https://www.youtube.com/playlist?list=PLCvlyb1ChyzHj_v2vXBkHZZTQDPBPBpBl',
            ],
            [
                'playlist_id'   => 'PLF8OvnXE7mSYHEWG7I9KIBkgxthFtBM9b',
                'title'         => 'دورة الهندسة المدنية الشاملة - من الصفر',
                'description'   => 'أساسيات الهندسة المدنية والإنشائية.',
                'thumbnail_url' => 'https://picsum.photos/seed/eng1/320/180',
                'channel_name'  => 'أكاديمية التسويق العربي',
                'category'      => 'الهندسة',
                'video_count'   => 68,
                'playlist_url'  => 'https://www.youtube.com/playlist?list=PLF8OvnXE7mSYHEWG7I9KIBkgxthFtBM9b',
            ],
            [
                'playlist_id'   => 'PLDoPjvoNmBAx_C6-KUoYcsuqkWA8HKZRY',
                'title'         => 'التصميم بالبرنامج الإلكتروني - بناء قاعدة برمجية',
                'description'   => 'تعلم التصميم الإلكتروني من الأساس.',
                'thumbnail_url' => 'https://picsum.photos/seed/ele1/320/180',
                'channel_name'  => 'إلكترونيات بالعربي',
                'category'      => 'الهندسة',
                'video_count'   => 45,
                'playlist_url'  => 'https://www.youtube.com/playlist?list=PLDoPjvoNmBAx_C6-KUoYcsuqkWA8HKZRY',
            ],
            [
                'playlist_id'   => 'PLknwEmKsW8OtM8mPSHfKGFSHDPRmXsVzb',
                'title'         => 'دورة تطوير تطبيقات الويب بإطار PHP Laravel',
                'description'   => 'تعلم Laravel من الصفر وبناء تطبيقات احترافية.',
                'thumbnail_url' => 'https://picsum.photos/seed/laravel1/320/180',
                'channel_name'  => 'أبو علي',
                'category'      => 'البرمجة',
                'video_count'   => 80,
                'playlist_url'  => 'https://www.youtube.com/playlist?list=PLknwEmKsW8OtM8mPSHfKGFSHDPRmXsVzb',
            ],
            [
                'playlist_id'   => 'PLF4oqdKHCAbKo8KEXj_RxJ6jomSdxIR7s',
                'title'         => 'أساسيات إدارة المشاريع - دورة شاملة للمبتدئين',
                'description'   => 'كل ما تحتاجه لإدارة مشاريعك بنجاح.',
                'thumbnail_url' => 'https://picsum.photos/seed/mgmt1/320/180',
                'channel_name'  => 'المدير الذكي',
                'category'      => 'إدارة الأعمال',
                'video_count'   => 52,
                'playlist_url'  => 'https://www.youtube.com/playlist?list=PLF4oqdKHCAbKo8KEXj_RxJ6jomSdxIR7s',
            ],
            [
                'playlist_id'   => 'PLdo4fOcmZ0oVsEtNQLa5R_Q_zPMXBVHhT',
                'title'         => 'دورة الرياضة الناجحة وريادة الأعمال',
                'description'   => 'كيف تبني شركتك الناشئة من الصفر.',
                'thumbnail_url' => 'https://picsum.photos/seed/biz1/320/180',
                'channel_name'  => 'ريادة عربية',
                'category'      => 'إدارة الأعمال',
                'video_count'   => 35,
                'playlist_url'  => 'https://www.youtube.com/playlist?list=PLdo4fOcmZ0oVsEtNQLa5R_Q_zPMXBVHhT',
            ],
            [
                'playlist_id'   => 'PLam8Wk3e0ql9ZEIhRLyGYiWVTjzQRiLqB',
                'title'         => 'دورة النسوي الرقمي الشاملة + من الصغر إلى الاحتراف',
                'description'   => 'استراتيجيات التسويق الرقمي المتقدمة.',
                'thumbnail_url' => 'https://picsum.photos/seed/dig1/320/180',
                'channel_name'  => 'أكاديمية التسويق العربي',
                'category'      => 'التسويق',
                'video_count'   => 110,
                'playlist_url'  => 'https://www.youtube.com/playlist?list=PLam8Wk3e0ql9ZEIhRLyGYiWVTjzQRiLqB',
            ],
        ];

        foreach ($courses as $data) {
            // Respect the deduplication rule even in the seeder
            Course::firstOrCreate(
                ['playlist_id' => $data['playlist_id']],
                $data
            );
        }

        $this->command->info('Demo courses seeded successfully (' . count($courses) . ' records).');
    }
}
