# RIPOTI KAMILI YA MCHAKATO WA MFUMO WA CBE (Integrated E-Logbook & GPS Attendance System)

**Mwandishi / Mtengenezaji:** MOHAMEDY HAMADI MOHAMED  
**Taasisi:** College of Business Education (CBE)  
**Ushirikiano wa Field:** Geological Survey of Tanzania (GST)  
**Tarehe ya Mradi:** Septemba 2026  

---

## UTANGULIZI NA LENGO KUU LA MFUMO
Mfumo huu umeundwa ili kutatua changamoto za kiutendaji na usimamizi wa wanafunzi wanaofanya mafunzo kwa vitendo (Field Practical Training / PT). 

Kijadi, wanafunzi hutumia daftari za karatasi (Logbooks) ambazo:
1. Ni rahisi kuchakachua taarifa (mwanafunzi kukaa nyumbani na kujaza wiki nzima bila kufika ofisini).
2. Huchelewesha usahihishaji wa walimu na wasimamizi wa mafunzo (Field Supervisors).
3. Hukosa uthibitisho wa kijiografia (GPS location verification).

Mfumo huu unaleta mapinduzi ya kidijitali kwa kuwawezesha wanafunzi kurekodi kazi zao mtandaoni, kusaini mahudhurio ya kila siku kupitia GPS Geofencing, na kuwapa wasimamizi uwezo wa kuhakiki na kutathmini kazi hizo kwa wakati halisi.

---

## 1. MIUNDO NA MAJUKUMU YA WATUMIAJI (USER ROLES & ACCESS)

Mfumo una ngazi tatu za watumiaji:

### A. Mkuu wa Mfumo (Administrator / Super Admin)
- **Akaunti:** Mohamedy Hamadi Mohamed (`mh4409684@gmail.com`).
- **Majukumu:**
  - Kuhakiki na kuidhinisha wanafunzi wapya waliojisajili mtandaoni (**Student Self-Registration Approval**).
  - Kusajili wasimamizi (Supervisors) na wahadhiri/staff. Watu hawa hawawezi kujisajili wenyewe; usajili wao ni haki ya kipekee ya Admin kwa ajili ya usalama wa chuo.
  - Kuwapangia wanafunzi mashirika ya field (mfano: GST - Geological Survey of Tanzania) na kuwaunganisha na wasimamizi wao.
  - Kutoa ripoti kamili za mahudhurio na utendaji kazi wa wanafunzi wote.

### B. Mwanafunzi (Student)
- Kujisajili mtandaoni kupitia fomu rasmi ya chuo.
- Kusubiri idhini (Approval) ya Admin kabla ya kuingia.
- Kurekodi shajara ya kazi ya kila siku (Daily E-Logbook).
- Kutuma ripoti ya kila mwisho wa wiki (Weekly Summary Report).
- Kusaini mahudhurio ya kila siku kupitia **GPS Geofence** akiwa eneo la kazi.

### C. Msimamizi wa Kazi (Field Supervisor)
- Kupokea orodha ya wanafunzi waliopangiwa chini yake kiotomatiki.
- Kusoma na kusahihisha E-Logbook na ripoti za wiki (Kukubali / Kukataa na kuweka maoni).
- Kuangalia rekodi za GPS za mahudhurio ya mwanafunzi.

---

## 2. MFUMO WA USAJILI WA WANAFUNZI NA IDHINI YA ADMIN (REGISTRATION & APPROVAL WORKFLOW)

Katika mifumo mikubwa ya vyuo vikuu, hairuhusiwi mwanafunzi au mtu asiyehusika kujisajili na kuanza kutumia mfumo moja kwa moja. Tumeweka mfumo madhubuti wa usalama:

```
[Mwanafunzi Mpya] 
       │
       ▼ (Hujaza Fomu Mtandaoni: Jina, Reg No, Kozi, Kampasi, Password)
[Hali ya Akaunti: Pending Approval (is_active = false)]
       │
       ├────► Akijaribu Kulogin kabla ya kukubaliwa: Mfumo unamzuia na kumpa ujumbe rasmi.
       │
       ▼
[Admin Dashboard: Pending Students Panel]
       │
       ▼ (Admin anapitia taarifa zake na kubonyeza "Accept & Activate")
[Akaunti Inawashwa (is_active = true)]
       │
       ▼
[Barua Pepe Rasmi ya CBE Inatumwa Kiotomatiki Kwenye Inbox ya Mwanafunzi]
       │
       ▼
[Mwanafunzi Anaingia Kwenye Portal na Kuanza Kutumia Mfumo]
```

---

## 3. MFUMO WA BARUA PEPE WA MOJA KWA MOJA (LIVE GMAIL SMTP ENGINE)

Ili kuondoa tatizo la zamani ambapo kodi za siri (OTP) zilikuwa zinaonekana kwenye kioo cha kompyuta:
- Mfumo umeunganishwa na **Google Mail Transfer Protocol (SMTP - Simple Mail Transfer Protocol)** kwa kutumia **App Password** maalum ya usalama ya Google (`dvisckdavidjohnson@gmail.com`).
- Kila barua pepe inatengenezwa ikiwa na muundo rasmi wa rangi za Chuo cha CBE (Navy Blue & Indigo) na nembo.

### Matukio Yanayotuma Barua Pepe Kiotomatiki:
1. **Admin Two-Factor Authentication (2FA OTP):** Kila Admin anapoingia kwenye mfumo, kodi ya nambari 6 inatumwa moja kwa moja kwenye inbox ya `mh4409684@gmail.com`. Bila hiyo kodi, hakuna anayeweza kuingia.
2. **Forgot Password:** Mtumiaji akisahau nenosiri, anaweka Username na Email yake. Ikiwa zinalingana na rekodi za chuo, anapokea OTP mpya kwenye inbox yake.
3. **Student Registration Approval:** Mwanafunzi akishakubaliwa na Admin, anapokea ujumbe wa pongezi na link ya kuanza masomo.

---

## 4. TEKNOLOJIA YA GPS GEOFENCING (MAHUDHURIO YA FIELD)

Ili kuzuia udanganyifu wa mahudhurio:
1. Kila shirika linalopokea wanafunzi (kwa mfano **Geological Survey of Tanzania - GST**, Dodoma) limesajiliwa na nukta zake halisi za kijiografia:
   - **Latitude:** `-6.173056`
   - **Longitude:** `35.748333`
   - **Geofence Radius:** Mita 200 kuzunguka ofisi.
2. Mwanafunzi anapofungua ukurasa wa Mahudhurio kwenye simu yake, mfumo unachukua GPS ya setilaiti ya kifaa chake (`navigator.geolocation`).
3. Mfumo unapiga hesabu kupitia kanuni ya hisabati ya **Haversine Formula**:
   \[
   d = 2r \arcsin\left(\sqrt{\sin^2\left(\frac{\Delta \phi}{2}\right) + \cos(\phi_1)\cos(\phi_2)\sin^2\left(\frac{\Delta \lambda}{2}\right)}\right)
   \]
4. Ikiwa mwanafunzi yupo ndani ya mita 200 kutoka ofisi ya GST, mfumo unamruhusu kusaini na kuweka alama ya kijani **"Within Radius - Check-in Valid"**.
5. Ikiwa yupo mbali (mfano nyumbani au hostel), mfumo unakataa kusaini na kumwambia umbali alionao kutoka ofisini.

---

## 5. MFUMO WA APP YA SIMU (PWA & APK INTEGRATION)

Ili kurahisisha matumizi ya wanafunzi bila kulazimika kuwa mbele ya kompyuta kila siku:
1. **Progressive Web App (PWA):**
   - Tumeongeza faili za `manifest.json` na `sw.js` (Service Worker) zenye rangi za CBE na icon za mfumo (`icon-192.png` na `icon-512.png`).
   - Mwanafunzi akifungua mfumo kwenye simu, anaona kitufe cha **"Pakua App"** au anaweza kubonyeza vitone 3 vya Chrome na kuchagua **"Install App"**.
   - Inajifunga kwenye simu kama programu kamili ya simu (Native App) bila browser bar ya juu.
2. **Uzalishaji wa Faili la APK (`.apk`):**
   - Link ya mfumo inawekwa kwenye **PWABuilder / Bubblewrap engine** ambayo inazalisha faili halisi la `.apk` linaloweza kusambazwa kwa wanafunzi kupitia WhatsApp au flash drive.

---

## 6. KUWEKA MFUMO HEWANI MASAA 24 (RENDER CLOUD DEPLOYMENT)

Ili mfumo ufanye kazi bila kutegemea kompyuta ya nyumbani kuwashwa:
1. **Ujenzi wa Kontena la Docker (Dockerfile):**
   - Tumetengeneza kontena lililoboreshwa la `php:8.2-fpm-alpine` lenye **Nginx Web Server**, **SQLite**, na viendelezi vyote vya PHP (GD, Zip, PDO).
2. **Kupandisha Kwenye GitHub:**
   - Mfumo wote pamoja na mabadiliko mapya umepandishwa kwenye hazina rasmi ya mtandaoni:
     🔗 `https://github.com/mh4409684-hub/MOHAMEDS-PROJECTS.git`
3. **Kuunganisha na Render.com:**
   - Kwenye Render, unachagua tu **New -> Web Service** na kuchagua hiyo repository.
   - Render inasoma faili la `render.yaml` na `Dockerfile` tuliloliandaa na kulirusha hewani mtandaoni masaa 24/7 bila gharama yoyote.

---

## HITIMISHO NA MAFANIKIO YA MRADI
Mradi huu umekidhi vigezo vyote vya kiwango cha juu cha mifumo ya Tehama ya kitaaluma:
- ✅ **Ulinzi thabiti wa kiusalama:** Uthibitisho wa hatua mbili (2FA) na ulinzi wa passwords kwa viwango vya kisasa vya kriptografia (`bcrypt`).
- ✅ **Mawasiliano ya moja kwa moja:** Barua pepe zinaingia kwenye Gmail za watumiaji papo hapo.
- ✅ **Uthibitisho wa uwepo kazini:** GPS Geofencing inahakikisha mwanafunzi anawajibika mahali pa kazi.
- ✅ **Urahisi wa matumizi:** Mfumo unafanya kazi kwenye kompyuta na kwenye simu kama App rasmi.
- ✅ **Kudumu hewani:** Tayari umesanidiwa kuwa hewani masaa 24 duniani kote.
