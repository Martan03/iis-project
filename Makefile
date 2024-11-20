login:=xsleza26

.PHONY: submit
submit:
	zip -r $(login).zip config public src templates .env composer.json \
		README.md
	zip $(login).zip -j docs/doc.html
