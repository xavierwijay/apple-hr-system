from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER, TA_LEFT
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.units import mm
from reportlab.platypus import (BaseDocTemplate, PageTemplate, Frame, Paragraph, Spacer, Table, TableStyle, PageBreak, KeepTogether, Preformatted, HRFlowable)
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.lib.colors import HexColor
from reportlab.lib.utils import simpleSplit
import os

OUT = 'output/pdf/Panduan-Latihan-Demo-Apple-HR-CPMK2.pdf'
os.makedirs(os.path.dirname(OUT), exist_ok=True)
FONT = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf'
FONT_BOLD = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf'
FONT_MONO = '/usr/share/fonts/truetype/dejavu/DejaVuSansMono.ttf'
if os.path.exists(FONT):
    pdfmetrics.registerFont(TTFont('DV', FONT))
    pdfmetrics.registerFont(TTFont('DV-Bold', FONT_BOLD))
    pdfmetrics.registerFont(TTFont('DV-Mono', FONT_MONO))
    F, FB, FM = 'DV', 'DV-Bold', 'DV-Mono'
else:
    F, FB, FM = 'Helvetica', 'Helvetica-Bold', 'Courier'

INK = HexColor('#171717')
MID = HexColor('#5C6168')
LIGHT = HexColor('#F3F4F5')
LINE = HexColor('#D7D9DC')
ACCENT = HexColor('#111827')
GREEN = HexColor('#EAF5EE')
RED = HexColor('#FFF0EF')
BLUE = HexColor('#EEF3FA')

class GuideDoc(BaseDocTemplate):
    def __init__(self, filename, **kw):
        super().__init__(filename, **kw)
        frame = Frame(18*mm, 17*mm, 174*mm, 263*mm, leftPadding=0, rightPadding=0, topPadding=0, bottomPadding=0, id='normal')
        self.addPageTemplates(PageTemplate(id='main', frames=frame, onPage=self.draw_page))
    def draw_page(self, canvas, doc):
        canvas.saveState()
        w, h = A4
        if doc.page > 1:
            canvas.setStrokeColor(LINE)
            canvas.setLineWidth(0.6)
            canvas.line(18*mm, h-14*mm, w-18*mm, h-14*mm)
            canvas.setFont(FB, 7.5)
            canvas.setFillColor(INK)
            canvas.drawString(18*mm, h-11*mm, 'APPLE DIRECTORY  /  PANDUAN DEMO CPMK 2')
        canvas.setStrokeColor(LINE)
        canvas.line(18*mm, 13*mm, w-18*mm, 13*mm)
        canvas.setFont(F, 7.5)
        canvas.setFillColor(MID)
        canvas.drawString(18*mm, 8.5*mm, 'Latihan presentasi • PBP • HR Information System')
        canvas.drawRightString(w-18*mm, 8.5*mm, f'{doc.page}')
        canvas.restoreState()

styles = getSampleStyleSheet()
styles.add(ParagraphStyle(name='CoverEyebrow', fontName=FB, fontSize=9, leading=12, textColor=HexColor('#6B7280'), spaceAfter=12, tracking=1.4))
styles.add(ParagraphStyle(name='CoverTitle', fontName=FB, fontSize=29, leading=34, textColor=INK, spaceAfter=9))
styles.add(ParagraphStyle(name='CoverSub', fontName=F, fontSize=12, leading=18, textColor=MID, spaceAfter=14))
styles.add(ParagraphStyle(name='H1x', fontName=FB, fontSize=18, leading=23, textColor=INK, spaceBefore=4, spaceAfter=10, keepWithNext=True))
styles.add(ParagraphStyle(name='H2x', fontName=FB, fontSize=11.5, leading=15, textColor=INK, spaceBefore=9, spaceAfter=5, keepWithNext=True))
styles.add(ParagraphStyle(name='Bodyx', fontName=F, fontSize=9, leading=13.1, textColor=INK, spaceAfter=5))
styles.add(ParagraphStyle(name='Smallx', fontName=F, fontSize=7.7, leading=10.5, textColor=MID, spaceAfter=3))
styles.add(ParagraphStyle(name='Bulletx', fontName=F, fontSize=8.7, leading=12.4, leftIndent=12, firstLineIndent=-8, bulletIndent=1, textColor=INK, spaceAfter=3))
styles.add(ParagraphStyle(name='Stepx', fontName=F, fontSize=8.8, leading=12.6, textColor=INK, spaceAfter=4))
styles.add(ParagraphStyle(name='Calloutx', fontName=F, fontSize=8.6, leading=12, textColor=INK))
styles.add(ParagraphStyle(name='CodeTitle', fontName=FB, fontSize=7.8, leading=10, textColor=HexColor('#DDE2E8'), backColor=ACCENT, borderPadding=(5,7,3,7), spaceBefore=3, spaceAfter=0))
styles.add(ParagraphStyle(name='TableHead', fontName=FB, fontSize=7.6, leading=10, textColor=colors.white))
styles.add(ParagraphStyle(name='TableCell', fontName=F, fontSize=7.4, leading=10, textColor=INK))
styles.add(ParagraphStyle(name='TableCellBold', fontName=FB, fontSize=7.4, leading=10, textColor=INK))
styles.add(ParagraphStyle(name='Quote', fontName=F, fontSize=9.2, leading=14, textColor=INK, leftIndent=8, rightIndent=8, spaceAfter=6))
styles.add(ParagraphStyle(name='TinyCode', fontName=FM, fontSize=6.65, leading=8.5, textColor=HexColor('#F4F4F5')))

P = lambda t, s='Bodyx': Paragraph(t, styles[s])
def bullet(t): return Paragraph('• ' + t, styles['Bulletx'])
def step(n, title, desc): return Paragraph(f'<b>{n}. {title}</b> — {desc}', styles['Stepx'])
def callout(title, body, color=BLUE):
    t=Table([[Paragraph(f'<b>{title}</b><br/>{body}', styles['Calloutx'])]], colWidths=[174*mm])
    t.setStyle(TableStyle([('BACKGROUND',(0,0),(-1,-1),color),('BOX',(0,0),(-1,-1),0.5,LINE),('LEFTPADDING',(0,0),(-1,-1),9),('RIGHTPADDING',(0,0),(-1,-1),9),('TOPPADDING',(0,0),(-1,-1),7),('BOTTOMPADDING',(0,0),(-1,-1),7)]))
    return t
def code(title, value):
    return [Paragraph(title, styles['CodeTitle']), Table([[Preformatted(value.strip('\n'), styles['TinyCode'])]], colWidths=[174*mm], style=TableStyle([('BACKGROUND',(0,0),(-1,-1),ACCENT),('LEFTPADDING',(0,0),(-1,-1),7),('RIGHTPADDING',(0,0),(-1,-1),7),('TOPPADDING',(0,0),(-1,-1),6),('BOTTOMPADDING',(0,0),(-1,-1),6)]))]
def table(headers, rows, widths):
    data=[[Paragraph(h, styles['TableHead']) for h in headers]]
    for row in rows:
        data.append([Paragraph(str(c), styles['TableCell']) for c in row])
    t=Table(data, colWidths=widths, repeatRows=1, hAlign='LEFT')
    t.setStyle(TableStyle([('BACKGROUND',(0,0),(-1,0),ACCENT),('GRID',(0,0),(-1,-1),0.35,LINE),('VALIGN',(0,0),(-1,-1),'TOP'),('LEFTPADDING',(0,0),(-1,-1),6),('RIGHTPADDING',(0,0),(-1,-1),6),('TOPPADDING',(0,0),(-1,-1),5),('BOTTOMPADDING',(0,0),(-1,-1),5),('ROWBACKGROUNDS',(0,1),(-1,-1),[colors.white,LIGHT])]))
    return t

story=[]
# COVER
story += [Spacer(1, 28*mm), P('PBP  •  PEMROGRAMAN BERORIENTASI PLATFORM', 'CoverEyebrow'),
          P('Panduan Latihan<br/>Demo CPMK 2', 'CoverTitle'),
          P('Apple Directory — Sistem Informasi Kepegawaian', 'CoverSub'),
          HRFlowable(width='100%', thickness=1.2, color=INK, spaceBefore=2, spaceAfter=14),
          P('Buku latihan untuk menyiapkan demo aplikasi, membuktikan alur di Postman, dan menjawab pertanyaan dosen dengan runtut.', 'Bodyx'),
          Spacer(1, 5*mm),
          table(['Target demo', 'Yang perlu dibuktikan'], [
              ('1. Aplikasi React', 'Login, lihat direktori, admin mengelola data, user hanya membaca.'),
              ('2. REST API', 'Endpoint HR mendukung baca dan operasi CRUD.'),
              ('3. JWT', 'Token dibutuhkan untuk endpoint terlindungi; token dapat refresh dan logout.'),
              ('4. Otorisasi role', 'Admin boleh menulis; user mendapat 403 untuk operasi tulis.'),
          ], [42*mm,132*mm]),
          Spacer(1, 10*mm),
          callout('Cara berlatih', 'Jalankan aplikasinya sambil mengikuti urutan halaman 2–7. Isi data demo sementara saja, dan hapus kembali setelah pembuktian.', GREEN),
          Spacer(1, 15*mm),
          P('Kredensial demo: admin@apple.com / password123  •  user@apple.com / password123', 'Smallx'),
          PageBreak()]

# PAGE 2 SETUP
story += [P('01  Persiapan sebelum presentasi', 'H1x'),
          P('Lakukan persiapan ini sebelum dosen datang supaya waktu presentasi dipakai untuk menunjukkan fitur, bukan memperbaiki setup.', 'Bodyx'),
          P('A. Jalankan aplikasi', 'H2x'),
          *code('Terminal — folder proyek', '''cd /home/xavierwjaya/Documents/PBP_MASYERE/apple-hr-system
npm run build
php artisan migrate --seed
php artisan serve --host=127.0.0.1 --port=8001'''),
          P('Biarkan terminal server tetap terbuka. Buka <b>http://127.0.0.1:8001/login</b> di browser. Database lokal proyek ini memakai MariaDB; pastikan service aktif dan koneksi .env benar. Jalankan <b>php artisan db:show</b> untuk memeriksa koneksi. Jalankan npm run build jika aset belum dibangun atau baru berubah.', 'Bodyx'),
          callout('Catatan database', 'migrate --seed menambahkan tabel yang belum ada dan mengisi akun serta contoh karyawan. Jangan gunakan migrate:fresh untuk latihan kecuali memang rela menghapus seluruh data database lokal.', RED),
          P('B. Pastikan akun dan tampilan siap', 'H2x'),
          bullet('<b>Admin:</b> admin@apple.com — password123'),
          bullet('<b>User:</b> user@apple.com — password123'),
          bullet('Contoh data direktori: AAPL-101 dan AAPL-102. Jangan hapus data contoh; buat satu record demo sementara untuk latihan CRUD.'),
          bullet('Siapkan browser di halaman login, terminal server, dan Postman. Pastikan tidak ada proses lain memakai port 8001.'),
          P('C. Siapkan Postman satu kali', 'H2x'),
          step(1, 'Import collection', 'di Postman klik <b>Import</b> → <b>Files</b>, pilih file <b>postman/Apple-HR-CPMK2.postman_collection.json</b> dari folder proyek.'),
          step(2, 'Periksa variables', 'di collection, base_url awalnya http://127.0.0.1:8001/api/v1. Skrip menyimpan token dan employeeDbId otomatis.'),
          step(3, 'Ikuti folder 1 sampai 6 berurutan', 'Login Admin → Admin CRUD → Login User → Bukti User Read Only → Login Admin Lagi → Hapus Record Demo.'),
          step(4, 'Lihat status dan tab Test', 'request berjalan dengan Send; response menampilkan status code dan JSON. Skrip collection juga memberi tanda test lulus.'),
          callout('Penting', 'Token berubah saat login user/admin. Folder terakhir hanya menghapus record sementara yang dibuat folder Admin CRUD. Jangan jalankan delete sebelum tes user selesai.', BLUE),
          PageBreak()]

# PAGE 3 UI
story += [P('02  Demo aplikasi React', 'H1x'),
          P('Urutan ini menunjukkan pengalaman pengguna dan perbedaan hak akses sebelum masuk ke pembuktian API.', 'Bodyx'),
          table(['Urutan', 'Aksi di layar', 'Bukti yang disebutkan'], [
              ('1', 'Buka /login. Tunjukkan halaman masuk Apple Directory.', 'Ini frontend React bertema HR.'),
              ('2', 'Masuk sebagai admin@apple.com / password123.', 'Header menampilkan identitas dan label Administrator.'),
              ('3', 'Tunjukkan direktori, jumlah karyawan, tim, pencarian.', 'Admin dapat membaca daftar dan mencari data.'),
              ('4', 'Klik Add employee. Isi record sementara, misalnya AAPL-DEMO-01.', 'Admin memiliki fitur Create.'),
              ('5', 'Edit judul / divisi record itu, simpan.', 'Admin memiliki fitur Update.'),
              ('6', 'Hapus hanya record AAPL-DEMO-01.', 'Admin memiliki fitur Delete; data contoh tetap aman.'),
              ('7', 'Sign out. Masuk sebagai user@apple.com / password123.', 'Label akses berubah menjadi Team member / Read only.'),
              ('8', 'Tunjukkan direktori. Perhatikan tombol tambah/edit/hapus tidak ada.', 'User masih bisa membaca data, tetapi UI menyembunyikan aksi tulis.'),
          ], [13*mm,82*mm,79*mm]),
          Spacer(1, 4*mm),
          P('Skrip bicara yang bisa dihafalkan', 'H2x'),
          callout('Pembuka', '“Aplikasi ini adalah sistem informasi kepegawaian bertema Apple. Frontend dibuat dengan React dan data karyawan dikelola melalui REST API Laravel.”'),
          Spacer(1, 3*mm),
          callout('Perbedaan role', '“Admin memiliki akses CRUD. User hanya membaca. Tombol disembunyikan di frontend, lalu saya buktikan sebentar lagi bahwa API juga menolak request tulis dari user.”', GREEN),
          Spacer(1, 3*mm),
          callout('Catatan istilah', 'Untuk demo ini, role <b>user</b> berarti staf pembaca direktori. Role <b>admin</b> berarti pengelola HR.'),
          P('Tips: jangan menghabiskan waktu mengetik panjang. Siapkan data sementara dan urutan klik sebelumnya; tetap tunjukkan satu kali create, update, dan delete secara nyata.', 'Smallx'),
          PageBreak()]

# PAGE 4 POSTMAN AUTH/READ
story += [P('03  Postman: login dan akses baca', 'H1x'),
          P('Buat request-request berikut di collection. Gunakan {{base_url}} agar URL mudah dipakai ulang. Di tab Authorization pilih “Inherit auth from parent” untuk endpoint yang memerlukan JWT.', 'Bodyx'),
          P('Request 1 — Login sebagai admin', 'H2x'),
          *code('POST {{base_url}}/auth/login  •  Body > raw > JSON', '''{
  "email": "admin@apple.com",
  "password": "password123"
}'''),
          P('Hasil yang diharapkan: <b>200 OK</b>; respons berisi token, token_type Bearer, expires_in, dan user.role = admin. Di tab Scripts > Post-response tempel skrip ini agar token otomatis tersimpan:', 'Bodyx'),
          *code('Post-response script — simpan token login', '''const data = pm.response.json();
pm.collectionVariables.set('token', data.token);
pm.collectionVariables.set('role', data.user.role);
pm.test('Login admin berhasil', () => pm.response.to.have.status(200));
pm.test('Role admin benar', () => pm.expect(data.user.role).to.eql('admin'));'''),
          P('Request 2 — Profil dari token', 'H2x'),
          *code('GET {{base_url}}/auth/me  •  Authorization: Bearer {{token}}', '''Tidak ada body'''),
          P('Hasil: <b>200 OK</b> dan data user admin. Ini membuktikan token dapat mengautentikasi pengguna.', 'Bodyx'),
          P('Request 3 — Baca daftar karyawan', 'H2x'),
          *code('GET {{base_url}}/employees  •  Authorization: Bearer {{token}}', '''Tidak ada body'''),
          P('Hasil: <b>200 OK</b> dan daftar dalam bentuk JSON di properti data. Data karyawan mencakup id, employee_id, nama, role_title, team_division, location, dan salary.', 'Bodyx'),
          P('Request 4 — Baca satu karyawan', 'H2x'),
          *code('GET {{base_url}}/employees/1  •  Authorization: Bearer {{token}}', '''Tidak ada body'''),
          P('Ganti angka 1 dengan id yang ada pada respons daftar. Hasil: <b>200 OK</b> dan detail satu karyawan.', 'Bodyx'),
          callout('Bukti yang diperlihatkan ke dosen', 'Klik request di Postman, tunjukkan method + URL, Bearer token, lalu status code dan response JSON. Jangan tampilkan atau kirim token ke orang lain; token berlaku seperti kredensial sementara.', BLUE),
          PageBreak()]

# PAGE 5 CRUD
story += [P('04  Postman: buktikan CRUD sebagai admin', 'H1x'),
          P('Pastikan token collection saat ini berasal dari login admin. Buat satu karyawan sementara dengan employee_id unik supaya aman dari duplikasi.', 'Bodyx'),
          P('Request 5 — Create', 'H2x'),
          *code('POST {{base_url}}/employees  •  Authorization Bearer {{token}}  •  Body JSON', '''{
  "employee_id": "AAPL-POSTMAN-01",
  "nama": "Demo CPMK Dua",
  "role_title": "HR Demo Specialist",
  "team_division": "People Operations",
  "location": "Apple Park, Cupertino",
  "salary": 80000
}'''),
          P('Hasil: <b>201 Created</b>. Respons data karyawan berisi id database. Tambahkan skrip untuk menyimpan id:', 'Bodyx'),
          *code('Post-response script — simpan id record baru', '''const body = pm.response.json();
pm.collectionVariables.set('employeeDbId', body.data.id);
pm.test('Record dibuat', () => pm.response.to.have.status(201));'''),
          P('Request 6 — Read record yang dibuat', 'H2x'),
          *code('GET {{base_url}}/employees/{{employeeDbId}}', '''Tidak ada body; Authorization Bearer {{token}}'''),
          P('Hasil: <b>200 OK</b>; pastikan employee_id bernilai AAPL-POSTMAN-01.', 'Bodyx'),
          P('Request 7 — Update', 'H2x'),
          *code('PUT {{base_url}}/employees/{{employeeDbId}}  •  Body JSON', '''{
  "employee_id": "AAPL-POSTMAN-01",
  "nama": "Demo CPMK Dua Updated",
  "role_title": "People Operations Analyst",
  "team_division": "People Operations",
  "location": "Apple Park, Cupertino",
  "salary": 85000
}'''),
          P('Hasil: <b>200 OK</b>. Endpoint update memerlukan seluruh field wajib. Periksa nama/judul/salary di respons.', 'Bodyx'),
          P('Request 8 — Delete (lakukan paling akhir)', 'H2x'),
          *code('DELETE {{base_url}}/employees/{{employeeDbId}}', '''Tidak ada body; Authorization Bearer {{token}}'''),
          P('Hasil: <b>204 No Content</b>. Setelah itu GET ke id tersebut seharusnya memberi <b>404 Not Found</b>. Record sementara sudah bersih.', 'Bodyx'),
          callout('Urutan yang aman', 'Create → simpan id → Read → Update → buktikan 403 untuk user (halaman 6) → login kembali sebagai admin → Delete record sementara.', GREEN),
          PageBreak()]

# PAGE 6 ROLE/AUTH
story += [P('05  Postman: bukti JWT dan pembatasan role', 'H1x'),
          P('Lakukan tes user menggunakan record sementara yang baru dibuat. Login user akan mengganti token collection.', 'Bodyx'),
          P('Login sebagai user', 'H2x'),
          *code('POST {{base_url}}/auth/login', '''{
  "email": "user@apple.com",
  "password": "password123"
}'''),
          P('Gunakan post-response script yang sama seperti login admin. Pastikan role yang tersimpan adalah user.', 'Bodyx'),
          P('Bukti: user boleh membaca', 'H2x'),
          *code('GET {{base_url}}/employees  •  Authorization Bearer {{token}}', '''Hasil yang diharapkan: 200 OK'''),
          P('Bukti: user tidak boleh menulis', 'H2x'),
          *code('POST {{base_url}}/employees  •  Authorization Bearer {{token}}  •  Body sama seperti request Create', '''Hasil yang diharapkan: 403 Forbidden'''),
          P('Untuk pembuktian yang lebih kuat, coba juga PUT /employees/{{employeeDbId}} dengan seluruh field wajib, lalu DELETE /employees/{{employeeDbId}}. Keduanya harus memberi <b>403 Forbidden</b> dan record sementara harus tetap ada.', 'Bodyx'),
          P('Bukti: autentikasi memang diperlukan', 'H2x'),
          *code('GET {{base_url}}/employees  •  Authorization > No Auth (hapus Bearer token)', '''Hasil yang diharapkan: 401 Unauthorized'''),
          P('Bukti: kredensial salah', 'H2x'),
          *code('POST {{base_url}}/auth/login  •  password salah', '''Hasil yang diharapkan: 401 Unauthorized'''),
          P('Refresh dan logout token (opsional bila waktu cukup)', 'H2x'),
          bullet('Refresh: POST {{base_url}}/auth/refresh dengan Bearer token aktif. Harus 200; jalankan script penyimpan token agar token baru menggantikan token lama.'),
          bullet('Logout: POST {{base_url}}/auth/logout dengan token aktif. Harus 200. Coba lagi GET /auth/me dengan token tersebut; harus 401.'),
          callout('Jangan tertukar', '401 = belum terautentikasi / token tidak valid. 403 = sudah terautentikasi, tetapi role tidak berhak melakukan aksi.', BLUE),
          PageBreak()]

# PAGE 7 WRAPUP
story += [P('06  Skrip presentasi dan tanya-jawab', 'H1x'),
          P('Contoh alur bicara sekitar 3–5 menit', 'H2x'),
          step(1, 'Tujuan', '“Aplikasi ini memenuhi topik CPMK 1 saya, yaitu sistem informasi kepegawaian, lalu dikembangkan untuk CPMK 2 dengan frontend React dan layanan API.”'),
          step(2, 'Tunjukkan UI', '“Saya login sebagai admin dan memperlihatkan operasi CRUD. Setelah logout saya login sebagai user; user tetap dapat melihat direktori, tetapi tidak memiliki tombol perubahan.”'),
          step(3, 'Buktikan backend', '“Sekarang saya tunjukkan endpoint API dan status responsnya di Postman. Admin mendapat 201 saat membuat data; user mendapat 403 untuk request tulis.”'),
          step(4, 'Tutup', '“Autentikasi menggunakan JWT. API memeriksa token dan role di backend, sehingga pembatasan tetap berlaku walaupun request dikirim langsung dari Postman.”'),
          P('Pertanyaan dosen yang mungkin muncul', 'H2x'),
          table(['Pertanyaan', 'Jawaban ringkas'], [
              ('Apa bedanya autentikasi dan otorisasi?', 'Autentikasi memeriksa siapa pengguna lewat JWT. Otorisasi memeriksa izin berdasarkan role admin/user.'),
              ('JWT dipakai di mana?', 'Client mengirim token di header Authorization: Bearer <token> untuk endpoint yang memerlukan login.'),
              ('Mengapa tombol user disembunyikan?', 'Agar UI sesuai hak akses. Keamanan tetap ditegakkan di server melalui middleware admin; tes Postman menunjukkan user mendapat 403.'),
              ('Apa bukti user read-only?', 'GET daftar karyawan memberi 200, sedangkan POST/PUT/DELETE user memberi 403.'),
              ('Apa arti status 401 dan 403?', '401: token tidak ada/tidak valid. 403: pengguna dikenali tetapi tidak punya izin.'),
              ('Bagaimana aplikasi menyimpan data?', 'Laravel menyediakan API. Data aplikasi ini tersimpan di MariaDB; React memanggil API untuk membaca dan mengubah record.'),
          ], [62*mm,112*mm]),
          P('Checklist malam sebelum demo', 'H2x'),
          bullet('Server dapat dijalankan; halaman /login terbuka.'),
          bullet('Seeder menyediakan dua akun; hafal email dan password.'),
          bullet('Postman punya collection variables base_url, token, employeeDbId.'),
          bullet('Latih membuat dan menghapus satu record sementara; jangan sentuh data contoh.'),
          bullet('Sebelum tes role, login ulang sesuai role. Token admin dan user berbeda.'),
          bullet('Tutup dengan status code dan bukti request, bukan hanya menyebut fitur.'),
          Spacer(1, 3*mm),
          callout('Kalau waktu presentasi pendek', 'Prioritaskan: login admin → satu siklus CRUD singkat → login user → GET 200 dan POST 403 di Postman. Refresh/logout bisa dijelaskan jika dosen bertanya.'),
          P('Endpoint ringkas: POST /auth/login • GET /auth/me • POST /auth/refresh • POST /auth/logout • GET /employees • GET /employees/{id} • POST /employees • PUT /employees/{id} • DELETE /employees/{id}', 'Smallx')]

# Ensure styles and page frames keep content within pages.
doc = GuideDoc(OUT, pagesize=A4, leftMargin=18*mm, rightMargin=18*mm, topMargin=18*mm, bottomMargin=18*mm,
               title='Panduan Latihan Demo Apple HR CPMK 2', author='OpenAI')
doc.build(story)
print(OUT)
