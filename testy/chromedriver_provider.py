from selenium import webdriver

class ChromeDriverProvider():
    def build_chromedriver(self):
        options = webdriver.ChromeOptions()
        options.add_argument('--ignore-ssl-errors=yes')
        options.add_argument('--ignore-certificate-errors')
        return webdriver.Chrome(options=options)
