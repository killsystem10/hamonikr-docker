#!/usr/bin/env python
import subprocess
import sys
import pymysql
import openai
import logging
from datetime import datetime, timedelta
from bs4 import BeautifulSoup
import re
import os

# 로깅 설정
logging.basicConfig(filename='xe-gpt.log', level=logging.INFO)

# 설정 파일을 대체할 변수 정의
db_config = {
    'HOST': 'localhost',
    'USER': 'hamonikr',
    'PASSWORD': 'exitem08',
    'DB': 'hamonikr'
}

API_KEY = os.getenv("OPENAI_API_KEY", "")

custom_instruction = """
하모니카OS(우분투 커널 기반, 리눅스 민트의 시나몬 데스크톱 환경 포함)를 사용하는 질문자가 커뮤니티 사이트의 게시판을 통해 특정 문제를 제기했습니다.

하모니카, 하모니카OS, HamoniKR, HamoniKR OS 등의 단어는 모두 하모니카OS를 의미합니다.
하모니카OS 는 linuxmint 를 기반으로, 한국 사용자들의 환경에 적합한 여러 프로그램을 추가하고 개작한 운영체제입니다.
하모니카 5.0은 linuxmint 20.1 Ulyssa 기반이며, Ubuntu 20.04 Focal 과 동일한 패키지입니다.
하모니카 6.0은 linuxmint 20.3 Una 기반이며, Ubuntu 20.4 Focal 과 동일한 패키지입니다.
하모니카 7.0은 linuxmint 21.2 Victoria 기반이며, Ubuntu 22.04 jammy 과 동일한 패키지입니다.
하모니카 8.0은 linuxmint 22 Wilma 기반이며, Ubuntu 24.04 Noble 과 동일한 패키지입니다.

=== 하모니카OS 매뉴얼 정보 ===

정보를 제공할 때 다음의 우선순위로 답변에 포함합니다:
1. 위에 포함된 하모니카OS 8.0 매뉴얼 정보를 우선적으로 참조
2. 이전 버전(5.0, 6.0, 7.0) 관련 질문인 경우 해당 버전의 매뉴얼 사이트 안내:
   - 하모니카 5.0: https://docs.hamonikr.org/hamonikr-5.0
   - 하모니카 6.0: https://docs.hamonikr.org/hamonikr-6.0
   - 하모니카 7.0: https://docs.hamonikr.org/hamonikr-7.0
3. 추가 참고 사이트:
   - 하모니카 커뮤니티 질의응답: https://hamonikr.org/hamoni_board
   - 우분투 질의응답: https://askubuntu.com/
   - 리눅스 민트 포럼: https://forums.linuxmint.com/

답변 방식:
1. 하모니카OS 8.0 관련 질문이라면, 위에 포함된 매뉴얼 정보를 바탕으로 해당 문제를 해결하기 위한 구체적인 방법을 한글로 자세히 설명합니다. 매뉴얼의 어느 부분을 참조했는지 명시하세요.
2. 하모니카OS 이전 버전(5.0, 6.0, 7.0) 관련 질문이라면, 해당 버전의 매뉴얼 사이트를 안내하고 일반적인 해결 방법을 제시합니다.
3. 매뉴얼에 해당 정보가 없거나 제공된 정보가 충분하지 않다면, 기본적인 해결 방법을 제시하면서 추가로 필요한 정보를 구체적으로 요청합니다.
4. 질문이 하모니카OS, 리눅스, 또는 시나몬 데스크톱 환경과 관련이 없는 경우에는 일반적인 응답을 해주세요.
5. 이 답변이 AI가 작성한 것임을 알려주고, 인공지능 답변을 그대로 사용하는 위험에 대해서 알려주세요.

주의사항: 코드를 설명하는 부분은 bash, python 등의 문구를 붙이지 말고 평문으로 출력해주세요.
주의사항: 강조를 위해 ** 으로 표시하는 부분은 평문으로 출력해주세요.
주의사항: 항상 친절하고 이해하기 쉬운 언어를 사용하여 답변하며, 하모니카OS, 리눅스 민트, 시나몬 데스크톱 환경과 관련된 문제 해결에 중점을 둡니다.
주의사항: 답변을 하기 전 전체 답변 내용을 검토해서, 제대로 구성되지 않은 문장이나, 문맥상 이상한 부분을 자연스럽게 수정하는 과정을 수행 후, 리눅스 전문가가 말하듯이 해주세요.
"""

# 매뉴얼 로드 함수
def load_manual_content():
    """
    하모니카OS 8.0 매뉴얼 텍스트 파일을 읽어서 반환
    """
    manual_content = ""
    
    # 스크립트 파일의 디렉토리를 기준으로 경로 설정
    script_dir = os.path.dirname(os.path.abspath(__file__))
    manual_dir = os.path.join(script_dir, "manual")
    
    # 하모니카OS 8.0 매뉴얼 파일만 로드
    manual_files = [
        "hamonikr-8.0.txt",
    ]
    
    for filename in manual_files:
        file_path = os.path.join(manual_dir, filename)
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                manual_content += f"\n=== {filename} ===\n"
                manual_content += f.read()
                manual_content += "\n" + "="*50 + "\n"
                logging.info(f"Loaded manual file: {file_path}")
        except FileNotFoundError:
            logging.warning(f"Manual file not found: {file_path}")
            continue
        except Exception as e:
            logging.error(f"Error reading manual file {file_path}: {e}")
            continue
    
    if not manual_content:
        logging.warning("No manual files were loaded")
    
    return manual_content

# GPT 모델 함수
def call_gpt_model(content, instruction):
    try:
        # HTML 태그 제거
        soup = BeautifulSoup(content, "html.parser")
        clean_content = soup.get_text()
        clean_content = re.sub(r'\s+', ' ', clean_content).strip()

        openai.api_key = API_KEY
        
        # 매뉴얼 내용 로드
        manual_content = load_manual_content()
        
        # 매뉴얼 내용을 포함한 확장된 instruction 생성
        enhanced_instruction = instruction.replace(
            "=== 하모니카OS 매뉴얼 정보 ===",
            f"=== 하모니카OS 매뉴얼 정보 ===\n{manual_content}"
        )
        
        # 시스템 프롬프트와 사용자 메시지 구분
        system_prompt = enhanced_instruction
        user_message = clean_content

        def estimate_token_count(text):
            # 한국어와 영어를 고려한 더 정확한 토큰 추정
            # 일반적으로 한국어는 1글자당 1-2토큰, 영어는 1단어당 1-2토큰
            korean_chars = len(re.findall(r'[가-힣]', text))
            english_words = len(re.findall(r'[a-zA-Z]+', text))
            other_chars = len(re.findall(r'[^\w\s가-힣]', text))
            
            # 보수적으로 추정: 한국어 1.5토큰/글자, 영어 1.3토큰/단어, 기타 1토큰/문자
            estimated_tokens = int(korean_chars * 1.5 + english_words * 1.3 + other_chars)
            return estimated_tokens

        # 시스템 프롬프트와 사용자 메시지 토큰 수 계산
        system_tokens = estimate_token_count(system_prompt)
        user_tokens = estimate_token_count(user_message)
        total_tokens = system_tokens + user_tokens
        
        # logging.info(f"System Token Count: {system_tokens}")
        # logging.info(f"User Token Count: {user_tokens}")
        # logging.info(f"Total Token Count: {total_tokens}")
        # logging.info(f"User Message: {user_message}")

        # GPT-4.1-nano의 컨텍스트 윈도우는 1,047,576 토큰
        # 응답을 위한 여유분을 고려하여 1,000,000 토큰으로 제한
        if total_tokens + 40000 > 1000000:
            logging.error(f"Token limit exceeded: {total_tokens} tokens in total")
            return "질문의 내용이 너무 길어서 처리할 수 없습니다."

        # gpt-4.1-nano 모델을 사용하여 API 호출
        response = openai.ChatCompletion.create(
            model="gpt-4.1-nano",
            messages=[
                {"role": "system", "content": system_prompt},
                {"role": "user", "content": user_message}
            ],
            max_tokens=12800  # 더 긴 응답 허용
        )
        return response.choices[0].message['content'].strip()

    except Exception as e:
        logging.error(f"GPT API call failed: {e}")
        return None

# MySQL 연결
try:
    db = pymysql.connect(host=db_config['HOST'], user=db_config['USER'], password=db_config['PASSWORD'], db=db_config['DB'])
    cursor = db.cursor()

except Exception as e:
    logging.error(f"Database connection failed: {e}")
    exit(1)

try:
    # 이전 댓글을 삭제하고 싶을 때
    #cursor.execute("DELETE FROM xe_comments WHERE user_id = 'chatgpt'")
    #cursor.execute("DELETE FROM xe_comments_list WHERE regdate >= 20231118000000")

    one_month_ago = (datetime.now() - timedelta(days=10)).strftime('%Y%m%d%H%M%S')
    logging.info("one_month_ago = %s", one_month_ago)
    cursor.execute("SELECT document_srl, module_srl, title, content FROM xe_documents WHERE module_srl = 112 ORDER BY regdate DESC LIMIT 1")

    rows = cursor.fetchall()

    for row in rows:
        document_srl, module_srl, title, content = row
        content = title + "\n" + content

        logging.info("\n\n\n\n --- ChatGPT Start ")
        logging.info(f"document_srl==>{document_srl}")
        cursor.execute("SELECT COUNT(*) FROM xe_comments WHERE document_srl = %s AND user_id = 'chatgpt'", (document_srl,))
        count = cursor.fetchone()[0]

        if count == 0:
            response = call_gpt_model(content, custom_instruction)
            if "exit" in response or not response:
                logging.info(f" ---- Not Matched Question (테이블에 데이터 삽입 안함.)---- :: {response}")
            else:
                response = response.replace("\n", "<br>").replace("```", "").replace("``", "").replace("**", "")

                cursor.execute("INSERT INTO xe_sequence (seq) VALUES (0)")
                db.commit()

                cursor.execute("SELECT LAST_INSERT_ID()")
                comment_srl = cursor.fetchone()[0]

                current_time = datetime.now().strftime('%Y%m%d%H%M%S')
                list_order = -1 * (comment_srl - 1)
                
                # Answer User (ChatGPT)
                member_srl = 129853
                ipaddress = "49.175.194.27"

                try:
                    gpt_answer_str = ""
                    logging.info(f"Answer::\n {gpt_answer_str}{response}")
                    response = gpt_answer_str + response

                    logging.info("\n\n####################테이블에 데이터 삽입####################")
                    cursor.execute("INSERT INTO xe_comments (comment_srl, document_srl, module_srl, content, user_id, user_name, nick_name, member_srl, email_address, homepage, regdate, last_update, ipaddress, list_order, status) VALUES (%s, %s, %s, %s, 'ChatGPT', 'ChatGPT', 'ChatGPT', %s, '', '', %s, %s, %s, %s, 1)", (comment_srl, document_srl, module_srl, response, member_srl, current_time, current_time, ipaddress, list_order))
                    logging.info(f"Inserted comment into xe_comments for document_srl: {document_srl}")

                    cursor.execute("INSERT INTO xe_comments_list (comment_srl, document_srl, head, arrange, module_srl, regdate, depth) VALUES (%s, %s, %s, %s, %s, %s, 0)", (comment_srl, document_srl, comment_srl, comment_srl, module_srl, current_time))
                    logging.info(f"Inserted comment into xe_comments_list for document_srl: {document_srl}")

                    cursor.execute("UPDATE xe_documents SET comment_count = comment_count + 1 WHERE document_srl = %s", (document_srl,))
                    logging.info(f"Updated comment_count in xe_documents for document_srl: {document_srl}")

                    db.commit()
                    logging.info(f"Comment posted successfully for document_srl: {document_srl} \n\n")

                except pymysql.MySQLError as e:
                    logging.error(f"MySQL operation failed: {e}")

except pymysql.MySQLError as e:
    logging.error(f"MySQL operation failed: {e}")

except Exception as e:
    logging.error(f"An error occurred: {e}")

finally:
    try:
        cursor.close()
        db.close()
    except Exception as e:
        logging.error(f"Failed to close the database: {e}")

