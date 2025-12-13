# HamoniKR.org Docker Container

[![Docker Hub](https://img.shields.io/badge/docker-hub-blue.svg)](https://hub.docker.com/r/hamonikr/hamonikr.org)
[![License](https://img.shields.io/badge/License-GPLv3-blue.svg)](LICENSE)

하모니카 커뮤니티 사이트(hamonikr.org)를 위한 완전한 도커 컨테이너 솔루션입니다. 레거시 PHP 애플리케이션, MySQL 데이터베이스, Apache 웹서버를 단일 컨테이너에 패키징하여 손쉬운 배포와 운영이 가능합니다.

## 🚀 주요 기능

- **웹서버**: Apache 2.4 + PHP 7.0 (XpressEngine 기반 하모니카 커뮤니티 사이트)
- **데이터베이스**: MySQL 5.7 with 자동 데이터 임포트
- **SSL/TLS**: Let's Encrypt 인증서 지원
- **AI 자동응닡**: ChatGPT 기반 커뮤니티 자동 답변 시스템 (`xe_gpt.py`)
- **GeoIP**: 지리 기반 접속 통계
- **봇 차단**: 불필요한 크롤링 봇 자동 차단

## 📋 시스템 요구사항

- Docker 20.04 이상
- Docker Compose (선택사항)
- 최소 2GB RAM
- 최소 5GB 디스크 공간

## 🐳 Docker Hub로 빠른 시작

```bash
docker run -d \
  --name hamonikr \
  --restart unless-stopped \
  -p 80:80 \
  -p 443:443 \
  -p 3306:3306 \
  -v /opt/hamonikr/mysql:/var/lib/mysql \
  -v /opt/hamonikr/logs/mysql:/var/log/mysql \
  -e MYSQL_ROOT_PASSWORD='your_password' \
  hamonikr/hamonikr.org:latest
```

## 🔧 직접 빌드 및 실행

### 1. 저장소 클론

```bash
git clone https://github.com/hamonikr/hamonikr-docker.git
cd hamonikr-docker
```

### 2. 필요한 파일 준비

다음 파일들은 `.gitignore`에 의해 제외되었으므로 별도로 준비해야 합니다:

- **files.tar.gz**: 웹사이트 첨부파일 아카이브 (1.8GB)
- **db/hamonikr.sql.gz**: MySQL 데이터베이스 덤프 (119MB)
- **certs/hamonikr/**: SSL 인증서 파일들

### 3. Docker 이미지 빌드

```bash
docker build -t hamonikr/hamonikr.org:latest .
```

### 4. 컨테이너 실행

```bash
docker run -d \
  --name hamonikr \
  --restart unless-stopped \
  -p 80:80 \
  -p 443:443 \
  -p 3306:3306 \
  -v /opt/hamonikr/mysql:/var/lib/mysql \
  -v /opt/hamonikr/logs/mysql:/var/log/mysql \
  -e MYSQL_ROOT_PASSWORD='exitem08EXITEM)*' \
  hamonikr/hamonikr.org:latest
```

## 🤖 AI 자동응닡 시스템 설정

`xe_gpt.py`는 ChatGPT API를 사용하여 커뮤니티 질문에 자동으로 답변하는 시스템입니다.

### 설정 방법

1. **OpenAI API 키 설정**:
   ```python
   API_KEY = "your_openai_api_key"
   ```

2. **데이터베이스 연결 정보 수정**:
   ```python
   db_config = {
       'HOST': 'localhost',
       'USER': 'hamonikr',
       'PASSWORD': 'your_db_password',
       'DB': 'hamonikr'
   }
   ```

3. **크론탭 설정**:
   ```bash
   # 10분마다 실행
   */10 * * * * /usr/bin/python3 /path/to/xe_gpt.py
   ```

### 매뉴얼 추가

AI 응답의 정확도를 높이기 위해 하모니카OS 매뉴얼을 추가할 수 있습니다:

```bash
mkdir manual
# 하모니카OS 8.0 매뉴얼 파일 추가
wget -O manual/hamonikr-8.0.txt https://docs.hamonikr.org/hamonikr-8.0/manual.txt
```

## 📁 프로젝트 구조

```
hamonikr-docker/
├── Dockerfile              # 도커 이미지 빌드 스크립트
├── start-services.sh       # 컨테이너 시작 스크립트
├── xe_gpt.py              # AI 자동응답 스크립트
├── apache/                # Apache 설정 파일
│   ├── hamonikr.conf      # HTTP 가상호스트 설정
│   ├── hamonikr-le-ssl.conf # HTTPS 가상호스트 설정
│   └── options-ssl-apache.conf
├── mysql/                 # MySQL 설정 파일
│   ├── my.cnf
│   └── mysql.conf.d/mysqld.cnf
├── certs/                 # SSL 인증서 (Git에서 제외)
├── db/                    # 데이터베이스 덤프 (Git에서 제외)
├── site/                  # 웹사이트 소스
│   └── hamonikr.org/
├── manual/                # AI용 매뉴얼 (선택사항)
├── files.tar.gz          # 웹사이트 첨부파일 (Git에서 제외)
├── .gitignore            # Git 제외 파일 목록
├── README.md             # 이 파일
└── LICENSE               # GPL v3 라이선스
```

## 🔄 데이터 백업 및 복원

### 데이터베이스 백업

```bash
docker exec hamonikr mysqldump -uroot -p hamonikr > hamonikr_backup_$(date +%Y%m%d).sql
```

### 데이터베이스 복원

```bash
docker exec -i hamonikr mysql -uroot -p hamonikr < hamonikr_backup.sql
```

### 파일 백업

```bash
# 볼륨에 백업된 파일들
tar -czf hamonikr_files_backup.tar.gz /opt/hamonikr/mysql /opt/hamonikr/logs
```

## 🚨 SSL 인증서 갱신

Let's Encrypt 인증서는 90일마다 갱신해야 합니다:

1. 인증서 갱신 (호스트 서버에서):
   ```bash
   certbot renew
   ```

2. 인증서 파일 교체:
   ```bash
   # certs/hamonikr/ 디렉토리에 새 인증서 복사
   cp /etc/letsencrypt/live/hamonikr.org-0001/*.pem certs/hamonikr/
   ```

3. Docker 이미지 재빌드:
   ```bash
   docker build -t hamonikr/hamonikr.org:latest .
   docker push hamonikr/hamonikr.org:latest
   ```

## 🔍 모니터링 및 로그

### 컨테이너 로그 확인

```bash
# 전체 로그
docker logs hamonikr

# 실시간 로그
docker logs -f hamonikr

# Apache 로그
docker exec hamonikr tail -f /var/log/apache2/hamonikr-error_log

# MySQL 로그
docker exec hamonikr tail -f /var/log/mysql/error.log

# AI 응답 로그
docker exec hamonikr tail -f /app/xe-gpt.log
```

## 🐛 문제 해결

### 자주 발생하는 문제

1. **컨테이너가 즉시 종료될 경우**:
   - MySQL 데이터 디렉토리 권한 확인
   - 디스크 공간 확인

2. **데이터베이스 연결 실패**:
   - MySQL 포트가 다른 서비스와 충돌하지 않는지 확인
   - 방화벽 설정 확인

3. **SSL 인증서 오류**:
   - 인증서 파일 경로 확인
   - 인증서 유효기간 확인

4. **AI 응답이 없는 경우**:
   - OpenAI API 키 확인
   - `xe-gpt.log` 로그 확인

## 📝 개발 및 기여

1. Fork 저장소
2. 기능 브랜치 생성 (`git checkout -b feature/AmazingFeature`)
3. 커밋 (`git commit -m 'Add some AmazingFeature'`)
4. 푸시 (`git push origin feature/AmazingFeature`)
5. Pull Request 생성

## 📄 라이선스

이 프로젝트는 GPL v3 라이선스 하에 배포됩니다. 자세한 내용은 [LICENSE](LICENSE) 파일을 참조하세요.

## 👥 연락처

- 프로젝트 홈페이지: https://hamonikr.org
- 커뮤니티: https://hamonikr.org/hamoni_board
- 이메일: admin@hamonikr.org

## 🙏 감사

- [XpressEngine](https://www.xpressengine.com/) - 강력한 PHP CMS 프레임워크
- [OpenAI](https://openai.com/) - AI 자동응답 시스템
- [Let's Encrypt](https://letsencrypt.org/) - 무료 SSL/TLS 인증서