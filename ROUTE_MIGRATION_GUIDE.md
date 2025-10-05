# Route Migration Guide

This document outlines the changes made to the routing structure for better organization and maintainability.

## Summary of Changes

1. **Moved route logic to PageController** - All page rendering logic that was in closures has been moved to `PageController.php`
2. **Organized routes into logical groups** - Routes are now grouped by functionality (auth, asisten, praktikan, etc.)
3. **Applied consistent naming conventions** - Route names now follow Laravel best practices
4. **Maintained backward compatibility** - Legacy routes are kept to avoid breaking existing frontend code

## Route Changes

### Authentication Routes

**Old Routes (Legacy - Still Available):**
```php
POST /loginAsisten
POST /loginPraktikan
POST /signupAsisten
POST /signupPraktikan
GET /logoutAsisten
GET /logoutPraktikan
```

**New Routes (Recommended):**
```php
POST /auth/asisten/login
POST /auth/praktikan/login
POST /auth/asisten/signup
POST /auth/praktikan/signup
GET /auth/asisten/logout
GET /auth/praktikan/logout
```

### Asisten Dashboard Routes

**Old Routes:**
```php
GET /asisten
GET /soal
GET /kelas
GET /modul
// etc...
```

**New Routes:**
```php
GET /asisten (dashboard)
GET /asisten/soal
GET /asisten/kelas
GET /asisten/modul
GET /asisten/plotting
GET /asisten/praktikum
GET /asisten/konfigurasi
GET /asisten/jawaban
GET /asisten/pelanggaran
GET /asisten/polling
GET /asisten/tp
GET /asisten/nilai
GET /asisten/history
GET /asisten/setpraktikan
GET /asisten/rating
GET /asisten/laporan
```

### Asisten API Routes

**Legacy Routes (Still Available):**
```php
POST /createKelas -> /asisten/kelas
POST /deleteKelas -> DELETE /asisten/kelas
POST /updateKelas -> PUT /asisten/kelas
POST /readDataKelas/{id} -> POST /asisten/kelas/{id}

POST /createModul -> /asisten/modul
POST /deleteModul/{id} -> DELETE /asisten/modul/{id}
POST /updateModul -> PUT /asisten/modul
POST /readModul -> POST /asisten/modul/read

POST /createTP -> /asisten/soal/tp
POST /updateTP -> PUT /asisten/soal/tp
POST /deleteTP/{id} -> DELETE /asisten/soal/tp/{id}

POST /createTA -> /asisten/soal/ta
POST /updateTA -> PUT /asisten/soal/ta
POST /deleteTA/{id} -> DELETE /asisten/soal/ta/{id}

POST /createTK -> /asisten/soal/tk
POST /updateTK -> PUT /asisten/soal/tk
POST /deleteTK/{id} -> DELETE /asisten/soal/tk/{id}

POST /createJurnal -> /asisten/soal/jurnal
POST /updateJurnal -> PUT /asisten/soal/jurnal
POST /deleteJurnal/{id} -> DELETE /asisten/soal/jurnal/{id}

POST /createMandiri -> /asisten/soal/mandiri
POST /updateMandiri -> PUT /asisten/soal/mandiri
POST /deleteMandiri/{id} -> DELETE /asisten/soal/mandiri/{id}

POST /createFitb -> /asisten/soal/fitb
POST /updateFitb -> PUT /asisten/soal/fitb
POST /deleteFitb/{id} -> DELETE /asisten/soal/fitb/{id}

POST /createJadwalJaga -> /asisten/jadwal-jaga
POST /deleteJadwalJaga -> DELETE /asisten/jadwal-jaga
POST /resetJadwalJaga -> DELETE /asisten/jadwal-jaga/reset

POST /cekPraktikum -> /asisten/praktikum/check
POST /createPraktikum -> /asisten/praktikum

POST /startPraktikum -> /asisten/praktikum/start
POST /continuePraktikum/{status} -> PUT /asisten/praktikum/continue/{status}
POST /stopPraktikum -> DELETE /asisten/praktikum/stop

POST /createLaporanPJ -> /asisten/laporan-pj
POST /currentLaporanPJ -> /asisten/laporan-pj/current
POST /updateLaporanPJ -> PUT /asisten/laporan-pj
POST /deleteLaporanPJ/{id} -> DELETE /asisten/laporan-pj/{id}

POST /makeHistory/jaga -> /asisten/history/jaga
POST /latestPJHistory/jaga -> /asisten/history/jaga/latest-pj
POST /deleteHistory/jaga -> DELETE /asisten/history/jaga
POST /makeHistory/izin -> /asisten/history/izin

POST /addPembahasanTP -> /asisten/tp/pembahasan
POST /activateTP/{modul_id} -> /asisten/tp/activate/{modul_id}
POST /deactivateTP/{modul_id} -> /asisten/tp/deactivate/{modul_id}

POST /kumpulTp -> /asisten/tp/kumpul
POST /getKumpulTp/{kelas_id}/{modul_id} -> /asisten/tp/kumpul/{kelas_id}/{modul_id}

POST /createFormNilai/{praktikan_id}/{modul_id} -> /asisten/nilai/form/{praktikan_id}/{modul_id}
POST /getAllJawaban/{praktikan_id}/{modul_id} -> /asisten/nilai/jawaban/{praktikan_id}/{modul_id}
POST /inputNilai -> /asisten/nilai
POST /getCurrentNilai/{praktikan_id}/{modul_id} -> /asisten/nilai/{praktikan_id}/{modul_id}

POST /deletePraktikanAlfa -> DELETE /asisten/praktikan/alfa
POST /setThisPraktikan/{nim}/{asisten_id}/{modul_id} -> /asisten/praktikan/set/{nim}/{asisten_id}/{modul_id}
POST /changePraktikanPass/{nim}/{new_pass} -> PUT /asisten/praktikan/password/{nim}/{new_pass}

GET /getProfilAsisten/{asisten_id} -> /asisten/profil/{asisten_id}
POST /updateDesc -> /asisten/update-desc
POST /saveConfiguration -> /asisten/konfigurasi/save
POST /activateJawaban -> /asisten/modul/jawaban-config

POST /readPesan -> /asisten/pesan
```

### Praktikan Routes

**Legacy Routes (Still Available):**
```php
GET /praktikan (dashboard)
GET /contact_asisten -> /praktikan/contact-asisten

POST /sendPesan -> /praktikan/pesan

POST /sendLaporan -> /praktikan/laporan
POST /getLaporan/{praktikan_id}/{modul_id} -> /praktikan/laporan/{praktikan_id}/{modul_id}

POST /sendJawabanTA -> /praktikan/jawaban/ta
POST /sendJawabanTK -> /praktikan/jawaban/tk
POST /sendJawabanJurnal -> /praktikan/jawaban/jurnal
POST /sendJawabanFitb -> /praktikan/jawaban/fitb
POST /sendJawabanMandiri -> /praktikan/jawaban/mandiri
POST /praktikanGetJurnal/{praktikan_id}/{modul_id} -> /praktikan/jawaban/jurnal/{praktikan_id}/{modul_id}

POST /sendTempJawabanTP -> /praktikan/tp/temp-jawaban
POST /saveJawabanTP -> /praktikan/tp/save-jawaban

POST /checkPolling -> /praktikan/polling/check
POST /savePolling -> /praktikan/polling

POST /getAllNilai/{praktikan_id} -> /praktikan/nilai/{praktikan_id}

POST /resetPass -> /praktikan/reset-password
```

### Shared Routes (Both Asisten and Praktikan)

**Legacy Routes:**
```php
POST /checkPraktikum -> /praktikum/check
POST /getModul/{id} -> /modul/{id}
POST /getPembahasanTP/{isEnglish} -> /tp/pembahasan/{isEnglish}
```

### Public API Routes

**Legacy Routes (Still Available):**
```php
GET /getSoalTP/{isEnglish}/{praktikan_id} -> /api/soal/tp/{isEnglish}/{praktikan_id}
GET /getSoalTA/{modul_id}/{kelas_id} -> /api/soal/ta/{modul_id}/{kelas_id}
GET /getSoalTK/{modul_id}/{kelas_id} -> /api/soal/tk/{modul_id}/{kelas_id}
GET /getSoalFITB -> /api/soal/fitb
GET /getSoalJURNAL -> /api/soal/jurnal
GET /getSoalRUNMOD -> /api/soal/runmod
GET /getSoalMANDIRI/{modul_id}/{kelas_id} -> /api/soal/mandiri/{modul_id}/{kelas_id}
```

## Frontend Migration Notes

### Important:
- **All legacy routes are still functional** for backward compatibility
- Frontend can be migrated gradually to use new routes
- New routes follow RESTful conventions:
  - POST for creating resources
  - PUT for updating resources
  - DELETE for deleting resources
  - GET for retrieving resources

### Recommended Migration Steps:

1. **Test the application** with existing legacy routes to ensure everything works
2. **Gradually update** Vue components to use new route structure
3. **Start with authentication routes** as they are the most critical
4. **Update API calls** one module at a time (Kelas, Modul, Soal, etc.)
5. **Remove legacy routes** once all frontend code is migrated

## Benefits of New Structure

1. **Better Organization** - Routes are logically grouped by functionality
2. **Easier Maintenance** - Controllers handle logic instead of route closures
3. **RESTful Conventions** - Proper use of HTTP methods (GET, POST, PUT, DELETE)
4. **Scalability** - Easier to add new features and endpoints
5. **Consistency** - Uniform naming conventions across the application
6. **Type Safety** - Controller methods are easier to type-hint and document

## PageController Methods

All page rendering logic has been moved to `PageController.php`:

- `welcome()` - Welcome page
- `about()` - About page
- `contact()` - Contact page
- `login()` - Login page
- `asisten()` - Asisten dashboard
- `praktikan()` - Praktikan dashboard
- `soal()` - Soal management
- `kelas()` - Kelas management
- `modul()` - Modul management
- `plotting()` - Plotting page
- `praktikum()` - Praktikum page
- `konfigurasi()` - Konfigurasi page
- `jawaban()` - Jawaban page
- `pelanggaran()` - Pelanggaran page
- `polling()` - Polling page
- `tp()` - Tugas Pendahuluan page
- `nilai()` - Nilai page
- `history()` - History page
- `setpraktikan()` - Set Praktikan page
- `lihatTp()` - Lihat TP page
- `rating()` - Rating page
- `allLaporan()` - All Laporan page
- `contactAsisten()` - Contact Asisten page

## Testing Checklist

- [ ] Login/Logout functionality (both Asisten and Praktikan)
- [ ] Signup functionality (both Asisten and Praktikan)
- [ ] All Asisten dashboard pages load correctly
- [ ] All Praktikan dashboard pages load correctly
- [ ] CRUD operations for Kelas
- [ ] CRUD operations for Modul
- [ ] CRUD operations for Soal (TP, TA, TK, Jurnal, Mandiri, FITB)
- [ ] Praktikum management
- [ ] Nilai management
- [ ] Polling functionality
- [ ] File upload functionality
- [ ] All API endpoints return correct data
