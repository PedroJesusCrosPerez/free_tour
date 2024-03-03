    function format(id) {
        switch (id) {
            case "1": return 'Lunes';
            case "2": return 'Martes';
            case "3": return 'Miércoles';
            case "4": return 'Jueves';
            case "5": return 'Viernes';
            case "6": return 'Sábado';
            case "7": return 'Domingo'
            default: return 'error en switch 216589413';
        }
    }

    function addTable(data) {
        var tr = $('<tr>');
        var tdPeriod = $('<td>');
        tdPeriod.html(data.date_start + ' - ' + data.date_end);
        var tdTime_start = $('<td>');
        tdTime_start.html(data.time_start);
        var tdPattern = $('<td>');
        if ( Array.isArray(data.patternf) ) {
            tdPattern.html(data.patternf.join(', '));
        } else {
            tdPattern.html(data.patternf);
        }
        var tdGuide = $('<td>');
        tdGuide.html(data.guide.name);

        tr.append(tdPeriod);
        tr.append(tdTime_start);
        tr.append(tdPattern);
        tr.append(tdGuide);

        $('table[name="programation"]').append(tr);
    }

    const programation = [];
    function addButtonProgram() {
        if (checkProgramation()) {
            var data = takeProgramationData();
            addTable(data);
            
            programation.push(data);
            console.log(programation);
        } else {
            console.log("Programación errónea =>",programation);
        }
    }

    function programAddProgramation() {
        // Program buttón programation "AÑADIR"
        $('#add').on('click', addButtonProgram);
    }

    function programOnChangeSelectFilter(field) {

        switch (field) {
            case "locality":
                $('#filter_locality').on('change', function() {
                    var locality_id = $(this).find('option:selected').data('locality_id');
                    toggleItemsByLocalityId([locality_id]);
                });
                break;

            case "province":
                $('#filter_province').on('change', function() {
                    var province_id = $(this).find('option:selected').data('province_id');
                    toggleItemsByProvinceId([province_id]);
                });
                
                break;
        
            default:
                break;
        }
        
    }

    // function createButtonProgram() {
    //     // console.log(takeData());
    // }

    // function programCreateProgramation() {
    //     // Program buttón programation "CREATE"
    //     $('#create').on('click', createButtonProgram);
    // }
